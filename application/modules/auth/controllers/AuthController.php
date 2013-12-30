<?php

class Auth_AuthController extends Zend_Controller_Action
{

    protected $_flashMessenger = null;

    public function init()
    {
        $this->_flashMessenger = $this->_helper->FlashMessenger;
    }

    public function postDispatch()
    {
        //passa para view as mensagens
        $this->view->flashMessenger = array_merge(
                $this->_flashMessenger->getMessages(), $this->_flashMessenger->getCurrentMessages()
        );
        $this->_flashMessenger->clearCurrentMessages();
    }

    public function indexAction()
    {

    }

    public function loginAction()
    {

//             $authModel->authenticate(array('login' => 'admin', 'password' => '123456'));
//        Application_Model_User::createUser( array('role_id' => 3, 'login' => 'admin', 'password' => '123456'));
//        createUser(array(‘login’=>’Guest’,'role_id’=>’1′,’password’=>’shocks’)
        // Criação do Objeto Formulário
        $form = new Application_Form_Login();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();
            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();

                try {

                    // Qualquer Manipulação de Dados
                    if (Application_Model_Auth::authenticate($data)) {
//                    Zend_Debug::dump($data);
                        $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                        $this->_redirect('/adm/index');
                    }
                } catch (Zend_Db_Table_Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        }

        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function logoutAction()
    {
        Application_Model_Auth::logOut();
        $this->_redirect('/auth/auth/login');
    }

}
