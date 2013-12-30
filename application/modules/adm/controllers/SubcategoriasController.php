<?php

class Adm_SubcategoriasController extends Zend_Controller_Action {

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
        $subcategorias = new Application_Model_Subcategorias();
        $this->view->rows = $subcategorias->fetchAll();
    }

    public function addAction() {
        // Criação do Objeto Formulário
        $form = new Application_Form_Subcategoria();
        $subcategorias = new Application_Model_Subcategorias();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();

            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $subcategorias->getAdapter()->beginTransaction();

                try {
                    if ($data['categorias']['categoria_id']) {
                        $data['categoria_id'] = $data['categorias']['categoria_id'];
                        unset($data['categorias']);
                    }

                    // Qualquer Manipulação de Dados
                    $subcategorias->insert($data);
                    $subcategorias->getAdapter()->commit();
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $subcategorias->getAdapter()->rollBack();
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
        $form = new Application_Form_Subcategoria();
        // $form->setAction('/adm/subcategoris/edit/id/' . $id);
        $subcategorias = new Application_Model_Subcategorias();
        $data = $subcategorias->find($id)->current()->toArray();

        if ($this->getRequest()->isPost()) {
            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();
            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {
                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $subcategorias->getAdapter()->beginTransaction();

                try {
                    if ($data['categorias']['categoria_id']) {
                        $data['categoria_id'] = $data['categorias']['categoria_id'];
                        unset($data['categorias']);
                    }
                    // Qualquer Manipulação de Dados
                    $where = $subcategorias->getAdapter()->quoteInto("sub_cat_id = ?", $id);
                    $subcategorias->update($data, $where);
                    $subcategorias->getAdapter()->commit();
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $subcategorias->getAdapter()->rollBack();
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
    
        $subcategorias = new Application_Model_Subcategorias();
        $subcategorias->getAdapter()->beginTransaction();
        try {
            $where = $subcategorias->getAdapter()->quoteInto("sub_cat_id = ?", $id);
            $subcategorias->delete($where);
            $subcategorias->getAdapter()->commit();
            $this->_flashMessenger->addMessage(array('success' => 'Dados apagados com sucesso!'));
            $this->_redirect('/adm/subcategorias');
        } catch (Zend_Db_Table_Exception $e) {
            $subcategorias->getAdapter()->rollBack();
            $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
        }
    }

}
