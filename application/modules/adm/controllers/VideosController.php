<?php

class Adm_VideosController extends Zend_Controller_Action
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
        $videos = new Application_Model_Videos();
        // lista de videos 
        $this->view->rows = $videos->fetchAll();
    }

    public function addAction()
    {
        // Criação do Objeto Formulário
        $form = new Application_Form_Video();
        $videos = new Application_Model_Videos();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();
            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $video_miniatura_url = $data["video_miniatura"];

                $subString = parse_url($video_miniatura_url);

                if (isset($subString['query']) && $subString['query']) {
                    parse_str($subString['query'], $output);
                    if (isset($output['v'])) {
                        $video_miniatura = $output['v'];
                    }
                } elseif (isset($subString['path']) && $subString['path'] != '/watch') {
                    $video_miniatura = str_replace('/', '', $subString['path']);
                }

                Zend_Debug::dump($data);

                $videos->getAdapter()->beginTransaction();
                try {
                    // Qualquer Manipulação de Dados
                    $data["video_miniatura"] = $video_miniatura;
                    $videos->insert($data);
                    $videos->getAdapter()->commit();
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $videos->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        }

        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function editAction()
    {
        $id = $this->_request->getParam('id');
        // Criação do Objeto Formulário
        $form = new Application_Form_Video();
        $videos = new Application_Model_Videos();
        $data = $videos->find($id)->current()->toArray();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();

            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $video_miniatura_url = $data["video_miniatura"];
                unset($data["video_miniatura"]);
                $subString = parse_url($video_miniatura_url);

                if (isset($subString['query']) && $subString['query']) {
                    parse_str($subString['query'], $output);
                    if (isset($output['v'])) {
                        $video_miniatura = $output['v'];
                    }
                } elseif (isset($subString['path']) && $subString['path'] != '/watch') {
                    $video_miniatura = str_replace('/', '', $subString['path']);
                }
                $data["video_miniatura"] = $video_miniatura;

                $videos->getAdapter()->beginTransaction();
                try {
                    // Qualquer Manipulação de Dados

                    $where = $videos->getAdapter()->quoteInto("video_id = ?", (int) $id);
                    $videos->update($data, $where);
                    $videos->getAdapter()->commit();
                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $videos->getAdapter()->rollBack();
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
        $videos = new Application_Model_Videos();
        $videos->getAdapter()->beginTransaction();
        try {
            $where = $videos->getAdapter()->quoteInto("video_id = ?", (int) $id);
            $videos->delete($where);
            $videos->getAdapter()->commit();
            $this->_flashMessenger->addMessage(array('success' => 'Dados apagados com sucesso!'));
            $this->_redirect('/adm/videos');
        } catch (Zend_Db_Table_Exception $e) {
            $videos->getAdapter()->rollBack();
            $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
        }
    }

}
