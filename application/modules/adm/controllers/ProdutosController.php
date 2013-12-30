<?php

class Adm_ProdutosController extends Zend_Controller_Action
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
        $produtos = new Application_Model_Produtos();
        // lista de produtos     
        $this->view->rows = $produtos->fetchAll();
    }

    public function addAction()
    {
        // Criação do Objeto Formulário
        $form = new Application_Form_Produto();
        $produtos = new Application_Model_Produtos();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();

            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $produtos->getAdapter()->beginTransaction();
                try {
                    if ($data['categorias']['categoria_id']) {
                        $data['categoria_id'] = $data['categorias']['categoria_id'];
                        unset($data['categorias']);
                    }
                    if ($data['subcategorias']['sub_cat_id']) {
                        $data['sub_cat_id'] = $data['subcategorias']['sub_cat_id'];
                        unset($data['subcategorias']);
                    }
//               
                    // Qualquer Manipulação de Dados
                    $produtos->insert($data);
                    $produtos->getAdapter()->commit();
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $produtos->getAdapter()->rollBack();
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
        $form = new Application_Form_Produto();
        $produtos = new Application_Model_Produtos();

        $id = $this->_request->getParam('id');
        $data = $produtos->find($id)->current()->toArray();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();

            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $produtos->getAdapter()->beginTransaction();
                try {
                    if ($data['categorias']['categoria_id']) {
                        $data['categoria_id'] = $data['categorias']['categoria_id'];
                        unset($data['categorias']);
                    }
                    if ($data['subcategorias']['sub_cat_id']) {
                        $data['sub_cat_id'] = $data['subcategorias']['sub_cat_id'];
                        unset($data['subcategorias']);
                    }
//              
                    // Qualquer Manipulação de Dados
                    $where = $produtos->getAdapter()->quoteInto("produto_id = ?", $id);
                    $produtos->update($data, $where);
                    $produtos->getAdapter()->commit();

                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $produtos->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        } else {
            $form->populate($data);
        }

        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $id = $this->_request->getParam('id');
        $produtos = new Application_Model_Produtos();
        $produtos->getAdapter()->beginTransaction();
        try {
            //apaga as imagens e as pastas das imagens
            $path = APPLICATION_PATH . "/../public/produtos/$id";
            $util = new Mylib_Util();
            $util->rmdir_recurse($path);

            // $where = $produtos->getAdapter()->quoteInto("produto_id = ?", (int) $id);
            // $produtos->delete($where);
            // delete cascade os registros de produtos_imagens e o proprio produto.
            $produtos->find($id)->current()->delete();

            $produtos->getAdapter()->commit();

            $this->_flashMessenger->addMessage(array('success' => 'Dados apagados com sucesso!'));
            $this->_redirect('/adm/produtos');
        } catch (Zend_Db_Table_Exception $e) {
            $produtos->getAdapter()->rollBack();
            $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
        }
    }

}
