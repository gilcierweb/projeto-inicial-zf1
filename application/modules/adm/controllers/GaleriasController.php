<?php

class Adm_GaleriasController extends Zend_Controller_Action
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
        $galerias = new Application_Model_Galerias();
        // lista de galerias
        $this->view->rows = $galerias->fetchAll();
    }

    public function addAction()
    {
        // Criação do Objeto Formulário
        $form = new Application_Form_Galeria();
        $galerias = new Application_Model_Galerias();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();
//            print_r($data);die;
            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $galerias->getAdapter()->beginTransaction();
                try {

                    $data['marca_id'] = $data['marcas']['marca_id'];
                    unset($data['marcas']);

                    // Qualquer Manipulação de Dados
                    $galerias->insert($data);
                    $galerias->getAdapter()->commit();
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $galerias->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        }
        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function editAction()
    {
        // Criação do Objeto Formulário
        $form = new Application_Form_Galeria();
        $galerias = new Application_Model_Galerias();

        $id = $this->_request->getParam('id');
        $data = $galerias->find($id)->current()->toArray();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();

            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $galerias->getAdapter()->beginTransaction();
                try {

                    $data['marca_id'] = $data['marcas']['marca_id'];
                    unset($data['marcas']);
//              
                    // Qualquer Manipulação de Dados
                    $where = $galerias->getAdapter()->quoteInto("galeria_id = ?", $id);
                    $galerias->update($data, $where);
                    $galerias->getAdapter()->commit();

                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $galerias->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        } else {
            $form->populate($data);
        }

        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

}
