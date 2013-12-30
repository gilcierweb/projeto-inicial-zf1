<?php

class Adm_ProdutosimagensController extends Zend_Controller_Action {

    protected $_flashMessenger = null;
    protected $_flashMessengerError = null;

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
        $id = $this->_request->getParam('id');

        $produtos = new Application_Model_Produtos();
        $produtosimagens = new Application_Model_Produtosimagens();

//        $find = $produtos->find($id)->current();
//        $img = $find->findDependentRowset('Application_Model_Produtosimagens');
//        $prodimgrowset = $prodimg->find($id)->current();
//        $prodimg = $produtos->find($id)->current();
        $rows = $produtosimagens->getAll($id);

        $this->view->deleteMultAjax = true;
        $this->view->id = $id;
        $this->view->rows = $rows;
    }

    public function uploadAction() {
        $produtosimagens = new Application_Model_Produtosimagens();

        $this->view->uploaddify = true;
        $this->view->id = $this->_request->getParam('id');
    }

    public function uploadedAction() {

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->getRequest()->isPost()) {

            $produto_id = $this->getRequest()->getParam('produto_id');

            $fotosG = APPLICATION_PATH . "/../public/produtos/$produto_id/fotosG/";
            $fotosD = APPLICATION_PATH . "/../public/produtos/$produto_id/fotosD/";
            $fotosP = APPLICATION_PATH . "/../public/produtos/$produto_id/fotosP/";

            $produtosimagens = new Application_Model_Produtosimagens();
            $produtosimagens->getAdapter()->beginTransaction();

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
                //$handle->image_contrast = 10;
                //$handle->jpeg_quality = 100;
                $handle->file_new_name_body = "1";

                // Definimos a pasta para onde a imagem thumbs ser� armazenada
                $handle->Process($fotosP);

                // Excluimos os arquivos temporarios
                $handle->Clean();
            }
            // Aqui somente recupero o nome da imagem caso queira fazer um insert em banco de dados
            $nome_da_imagem = $handle->file_dst_name;

            try {
                $data['produto_id'] = $produto_id;
                $data['prod_img_imagem'] = $nome_da_imagem;
                $produtosimagens->insert($data);
                $produtosimagens->getAdapter()->commit();
//                $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
            } catch (Zend_Db_Table_Exception $e) {
                $produtosimagens->getAdapter()->rollBack();
//                $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
            } catch (Zend_Db_Exception $e) {
                $produtosimagens->getAdapter()->rollBack();
//                $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
            } catch (Exception $e) {
                $produtosimagens->getAdapter()->rollBack();
//                $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
            }
        }
    }

    public function imgcapaAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);

        $produto_id = $this->_request->getParam('id');
        $img = $this->_request->getParam('img');
        $produtos = new Application_Model_Produtos();
        $produtos->getAdapter()->beginTransaction();
//        echo '<pre>';
//        print_r($img);
//        print_r($produto_id);
//        die();
        try {

            $data['produto_img_capa'] = $img;

            // Qualquer Manipulação de Dados
            $where = $produtos->getAdapter()->quoteInto("produto_id = ?", $produto_id);
            $produtos->update($data, $where);
            $produtos->getAdapter()->commit();

            $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
            $this->_redirect('adm/produtosimagens/index/id/' . $produto_id);
        } catch (Zend_Db_Table_Exception $e) {
            $produtos->getAdapter()->rollBack();
            $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
        }
    }

    public function deleteAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $produto_id = $this->_request->getParam('id');
        $dataForm = $this->getRequest()->getPost();
        //pega os ids das imagens em array
        $ids = $dataForm['id_img'];
//        echo '<pre>';
//        print_r($dataForm);
//        print_r($ids);
        $produtosimagens = new Application_Model_Produtosimagens();
        $produtosimagens->getAdapter()->beginTransaction();
//        echo implode(',', $ids);
//        die('gil');
        $where = $produtosimagens->getAdapter()->quoteInto("prod_img_id IN (?)", $ids);

        try {

            foreach ($produtosimagens->fetchAll($where) as $row) {
                echo $prod_img_imagem = $row->prod_img_imagem;
                echo $fotosG = APPLICATION_PATH . "/../public/produtos/$produto_id/fotosG/$prod_img_imagem";
                $fotosD = APPLICATION_PATH . "/../public/produtos/$produto_id/fotosD/$prod_img_imagem";
                $fotosP = APPLICATION_PATH . "/../public/produtos/$produto_id/fotosP/$prod_img_imagem";
                @unlink($fotosG);
                @unlink($fotosD);
                @unlink($fotosP);
            }
            $produtosimagens->delete($where);
            $produtosimagens->getAdapter()->commit();
            $this->_flashMessenger->addMessage(array('success' => 'Dados apagados com sucesso!'));
//            $this->_redirect('/adm/subcategorias');
        } catch (Zend_Db_Table_Exception $e) {
            $produtosimagens->getAdapter()->rollBack();
            $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
        }
    }

    public function upload_oldAction() {
// Criação do Objeto Formulário
        $form = new Application_Form_Produtosimagens();
        $produtosimagens = new Application_Model_Produtosimagens();
//  $handle = new Mylib_Classupload_Classupload();
        $data = array();

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {
            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();
            // Solicitamos ao Formulário que Verifique os Dados
            // Preenchimento dos Dados no Formulário
            if ($form->isValid($data)) {
                // Dados Filtrados pelo Formulário
                $data = $form->getValues();
                $produtosimagens->getAdapter()->beginTransaction();

                if ($data['produtos']['produto_id']) {
                    $data['produto_id'] = $data['produtos']['produto_id'];
                    unset($data['produtos']);
                }

                $upload = new Zend_File_Transfer_Adapter_Http();
//                $upload->addValidator('Size', false, 5102400, 'prod_img_imagem');
                $upload->setDestination(APPLICATION_PATH . '/../public/images/upload');
                $files = $upload->getFileInfo();

                try {

                    foreach ($files as $file => $info) {

                        if ($upload->isUploaded($file)) {
                            $upload->receive($file);
                            $data['prod_img_imagem'] = $info['name'];
                            $produtosimagens->insert($data);
                            $produtosimagens->getAdapter()->commit();
                        }
                    }

                    $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                } catch (Zend_Db_Table_Exception $e) {
                    $produtosimagens->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_Db_Exception $e) {
                    $produtosimagens->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Exception $e) {
                    $produtosimagens->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        }

        $this->view->uploaddify = true;
        $this->view->id = $this->_request->getParam('id');

        //$this->view->form Envio para a Camada de Visualização
        $this->view->form = $form;
    }

//    public function createAction()
//    {
//        $pageForm = new Form_PageForm();
//        if ($this->getRequest()->isPost()) {
//            if ($pageForm->isValid($_POST)) {
//// create a new page item
//                $itemPage = new CMS_Content_Item_Page();
//                $itemPage->name = $pageForm->getValue('name');
//                $itemPage->headline = $pageForm->getValue('headline');
//                $itemPage->description = $pageForm->getValue('description');
//                $itemPage->content = $pageForm->getValue('content');
//// upload the image
//                if ($pageForm->image->isUploaded()) {
//                    $pageForm->image->receive();
//                    $itemPage->image = '/images/upload/' .
//                            basename($pageForm->image->getFileName());
//                }
//// save the content item
//                $itemPage->save();
//                return $this->_forward('list');
//            }
//        }
//        $pageForm->setAction('/page/create');
//        $this->view->form = $pageForm;
//$mail = new Zend_Mail();
//// configure and create the SMTP connection
//$config = array('auth' => 'login',
// 'username' => 'myusername',
// 'password' => 'password');
//$transport = new Zend_Mail_Transport_Smtp('mail.server.com', $config);
//// set the subject
//$mail->setSubject($subject);
//// set the message's from address to the person who submitted the form
//$mail->setFrom($email, $sender);
//// for the sake of this example you can hardcode the recipient
//$mail->addTo('webmaster@somedomain.com', 'webmaster');
//// add the file attachment
//$fileControl = $frmContact->getElement('attachment');
//if($fileControl->isUploaded()) {
//$attachmentName = $fileControl->getFileName();
//$fileStream = file_get_contents($attachmentName);
//// create the attachment
//$attachment = $mail->createAttachment($fileStream);
//$attachment->filename = basename($attachmentName);
//}
//// it is important to provide a text only version in addition to the html message
//$mail->setBodyHtml($htmlMessage);
//$mail->setBodyText($message);
////send the message, now using SMTP transport
//$result = $mail->send($transport);
//    }

    public function upload2Acition($user_id, $email) {

        if ($user_id && $email) {
            $adapter = new Zend_File_Transfer_Adapter_Http();
            $user_path = PUBLIC_PATH . $this->uploads_rel . $user_id;

            if (!file_exists($user_path))
                mkdir($user_path);

            $adapter->setDestination(PUBLIC_PATH . $this->uploads_rel . $user_id);
            $adapter->addValidator('Extension', false, 'jpg,png,gif');

            $files = $adapter->getFileInfo();
            foreach ($files as $file => $info) {
                $name = $adapter->getFileName($file);

// you could apply a filter like this too (if you want), to rename the file:     
//  $name = ExampleLibrary::generateFilename($name);
//  $adapter->addFilter('rename', $user_path . '/' .$name);
// file uploaded & is valid
                if (!$adapter->isUploaded($file))
                    continue;
                if (!$adapter->isValid($file))
                    continue;

// receive the files into the user directory
                $adapter->receive($file); // this has to be on top

                $fileclass = new stdClass();

// we stripped out the image thumbnail for our purpose, primarily for security reasons
// you could add it back in here.
                $fileclass->name = str_replace(PUBLIC_PATH . $this->uploads_rel, 'New Image Upload Complete:   ', preg_replace('/\d\//', '', $name));
                $fileclass->size = $adapter->getFileSize($file);
                $fileclass->type = $adapter->getMimeType($file);
                $fileclass->delete_url = '/user/upload';
                $fileclass->delete_type = 'DELETE';
//$fileclass->error = 'null';
                $fileclass->url = '/';

                $datas[] = $fileclass;
            }
        }
    }

}
