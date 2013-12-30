<?php

class Adm_CategoriasController extends Zend_Controller_Action {

    public $_flashMessenger = null;

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
        $categorias = new Application_Model_Categorias();
        // lista de categorias     
        $this->view->rows = $categorias->fetchAll();
    }

    public function addAction() {
        // Criação do Objeto Formulário
        $form = new Application_Form_Categoria();
        $categorias = new Application_Model_Categorias();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();
            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $categorias->getAdapter()->beginTransaction();
                try {
                    // Qualquer Manipulação de Dados
                    $categorias->insert($data);
                    $categorias->getAdapter()->commit();
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $categorias->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        }

        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function editAction() {
        $id = $this->_request->getParam('id');
        // Criação do Objeto Formulário
        $form = new Application_Form_Categoria();
        $categorias = new Application_Model_Categorias();
        $data = $categorias->find($id)->current()->toArray();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();

            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $categorias->getAdapter()->beginTransaction();
                try {
                    // Qualquer Manipulação de Dados
                    $where = $categorias->getAdapter()->quoteInto("categoria_id = ?", (int) $id);
                    $categorias->update($data, $where);
                    $categorias->getAdapter()->commit();
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $categorias->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        } else {
            $form->populate($data);
        }

        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function deleteAction() {
        $id = $this->_request->getParam('id');
        $categorias = new Application_Model_Categorias();
        $categorias->getAdapter()->beginTransaction();
        try {
            $where = $categorias->getAdapter()->quoteInto("categoria_id = ?", (int) $id);
            $categorias->delete($where);
            $categorias->getAdapter()->commit();
            $this->_flashMessenger->addMessage(array('success' => 'Dados apagados com sucesso!'));
            $this->_redirect('/adm/categorias');
        } catch (Zend_Db_Table_Exception $e) {
            $categorias->getAdapter()->rollBack();
            $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
        }
    }

}
