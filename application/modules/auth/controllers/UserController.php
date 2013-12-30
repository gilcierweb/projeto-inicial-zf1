<?php

class Auth_UserController extends Zend_Controller_Action {

    protected $_flashMessenger = null;

    public function init() {
        $this->_flashMessenger = $this->_helper->FlashMessenger;
    }

    public function postDispatch() {
        //passa para view as mensagens
        $this->view->flashMessenger = array_merge(
                $this->_flashMessenger->getMessages(), $this->_flashMessenger->getCurrentMessages()
        );
        $this->_flashMessenger->clearCurrentMessages();
    }

    public function indexAction() {
        // action body
    }

    public function testeAction() {
        $AclToRole = new Application_Model_Acltorole();
        $form = new Application_Form_Acl();
        if ($this->getRequest()->isPost()) {
            $data = $this->getRequest()->getPost();
            if ($form->isValid($data)) {
                $data = $form->getValues();
                $AclToRole->getAdapter()->beginTransaction();
                try {
                    Zend_Debug::dump($data);
                    $role_id = $data['role']['role_id'];
                    foreach ($data['acl']['acl_id'] as $row) {
                        $data2['acl_id'] = $row;
                        $data2['role_id'] = $role_id;
                        echo '<pre>';
                        print_r($data2);
                        echo '</pre>';
                        $AclToRole->insert($data2);
                        $AclToRole->getAdapter()->commit();
                    }
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $AclToRole->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        }
        $this->view->form = $form;
    }

}
