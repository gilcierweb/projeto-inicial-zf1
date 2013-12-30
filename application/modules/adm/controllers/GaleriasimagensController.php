<?php

class Adm_GaleriasimagensController extends Zend_Controller_Action
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
        $id = $this->_request->getParam('id');

        $galerias = new Application_Model_Galerias();
        $galeriasimagens = new Application_Model_Galeriasimagens();

//        $find = $galerias->find($id)->current();
//        $img = $find->findDependentRowset('Application_Model_Produtosimagens');
//        $prodimgrowset = $prodimg->find($id)->current();
//        $prodimg = $galerias->find($id)->current();


        $rows = $galeriasimagens->getAll($id);

        $this->view->deleteMultAjaxGalerias = true;
        $this->view->id = $id;
        $this->view->rows = $rows;
    }

    public function uploadAction()
    {
        $galeriasimagens = new Application_Model_Galeriasimagens();

        $this->view->uploaddifyGalerias = true;
        $this->view->id = $this->_request->getParam('id');
    }

    public function uploadedAction()
    {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->getRequest()->isPost()) {

            $galeria_id = $this->getRequest()->getParam('galeria_id');

            $fotosG = APPLICATION_PATH . "/../public/galerias/$galeria_id/fotosG/";
            $fotosD = APPLICATION_PATH . "/../public/galerias/$galeria_id/fotosD/";
            $fotosP = APPLICATION_PATH . "/../public/galerias/$galeria_id/fotosP/";

            $galeriasimagens = new Application_Model_Galeriasimagens();
            $galeriasimagens->getAdapter()->beginTransaction();

            $Filedata = $_FILES["Filedata"];
            // Instanciamos o objeto Upload
            $handle = new Mylib_Classupload_Classupload();
            $handle->upload($Filedata);

            // Então verificamos se o arquivo foi carregado corretamente
            if ($handle->uploaded) {
                $handle->image_resize = true;
                // $handle->image_ratio_y = false;
                $handle->image_ratio_y = true;
                $handle->image_x = 640;
//                $handle->jpeg_quality = 100;
                $handle->file_new_name_body = "1";

                // Definimos a pasta para onde a imagem maior será armazenada fotosG
                $handle->Process($fotosG);

                // Aqui nos devifimos nossas configurações de imagem do thumbs fotosD
                $handle->image_resize = true;
                $handle->image_ratio_y = false;
                $handle->image_ratio_crop = true;
                $handle->image_x = 155;
                $handle->image_y = 155;
                // $handle->image_contrast = 10;
                //$handle->jpeg_quality = 100;
                $handle->file_new_name_body = "1";

                // Definimos a pasta para onde a imagem thumbs será armazenada
                $handle->Process($fotosD);

                // Aqui nos devifimos nossas configurações de imagem do thumbs fptosP
                $handle->image_resize = true;
                $handle->image_ratio_y = false;
                $handle->image_x = 185;
                $handle->image_y = 120;
                $handle->image_contrast = 10;
               // $handle->jpeg_quality = 100;
                $handle->file_new_name_body = "1";

                // Definimos a pasta para onde a imagem thumbs ser� armazenada
                $handle->Process($fotosP);

                // Excluimos os arquivos temporarios
                $handle->Clean();
            }
            // Aqui somente recupero o nome da imagem caso queira fazer um insert em banco de dados
            $nome_da_imagem = $handle->file_dst_name;

            try {
                $data['galeria_id'] = $galeria_id;
                $data['galeria_imagem'] = $nome_da_imagem;
                $galeriasimagens->insert($data);
                $galeriasimagens->getAdapter()->commit();
//                $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
            } catch (Zend_Db_Table_Exception $e) {
                $galeriasimagens->getAdapter()->rollBack();
//                $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
            } catch (Zend_Db_Exception $e) {
                $galeriasimagens->getAdapter()->rollBack();
//                $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
            } catch (Exception $e) {
                $galeriasimagens->getAdapter()->rollBack();
//                $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
            }
        }
    }

    public function imgcapaAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $galeria_id = $this->_request->getParam('id');
        $img = $this->_request->getParam('img');
        $galerias = new Application_Model_Galerias();
        $galerias->getAdapter()->beginTransaction();

        try {

            $data['galeria_img_capa'] = $img;

            // Qualquer Manipulação de Dados
            $where = $galerias->getAdapter()->quoteInto("galeria_id = ?", $galeria_id);
            $galerias->update($data, $where);
            $galerias->getAdapter()->commit();

            $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
            $this->_redirect('adm/galeriasimagens/index/id/' . $galeria_id);
        } catch (Zend_Db_Table_Exception $e) {
            $galerias->getAdapter()->rollBack();
            $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
        }
    }

    public function deleteAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $galeria_id = $this->_request->getParam('id');
        $dataForm = $this->getRequest()->getPost();
        //pega os ids das imagens em array
        $ids = $dataForm['id_img'];

        $galeriasimagens = new Application_Model_Galeriasimagens();
        $galeriasimagens->getAdapter()->beginTransaction();

        $where = $galeriasimagens->getAdapter()->quoteInto("galeria_imagem_id IN (?)", $ids);

        try {

            foreach ($galeriasimagens->fetchAll($where) as $row) {
                $galeria_imagem = $row->galeria_imagem;
                echo $fotosG = APPLICATION_PATH . "/../public/galerias/$galeria_id/fotosG/$galeria_imagem";
                $fotosD = APPLICATION_PATH . "/../public/galerias/$galeria_id/fotosD/$galeria_imagem";
                $fotosP = APPLICATION_PATH . "/../public/galerias/$galeria_id/fotosP/$galeria_imagem";
                @unlink($fotosG);
                @unlink($fotosD);
                @unlink($fotosP);
            }
            $galeriasimagens->delete($where);
            $galeriasimagens->getAdapter()->commit();
            $this->_flashMessenger->addMessage(array('success' => 'Dados apagados com sucesso!'));
//            $this->_redirect('/adm/subcategorias');
        } catch (Zend_Db_Table_Exception $e) {
            $galeriasimagens->getAdapter()->rollBack();
            $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
        }
    }

   

}
