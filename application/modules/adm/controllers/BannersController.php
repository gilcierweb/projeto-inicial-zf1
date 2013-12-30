<?php

class Adm_BannersController extends Zend_Controller_Action
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
        $banners = new Application_Model_Banners();
        // lista de banners 
        $this->view->rows = $banners->fetchAll();
    }

    public function addAction()
    {

        // Criação do Objeto Formulário
        $form = new Application_Form_Banner();
        $banners = new Application_Model_Banners();
        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();
            $path = APPLICATION_PATH . '/../public/banners/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            if ($form->isValid($data)) {

                try {
                    // Dados Filtrados pelo Formulário
                    $banners->getAdapter()->beginTransaction();
                    //gambi para renomear arquivos
                    $upload = new Zend_File_Transfer_Adapter_Http();
//                $upload->addValidator('Size', false, array('min' => 100,
//                    'max' => 1150000,
//                    'bytestring' => true));
                    $upload->addValidator('ImageSize', false, array(
                        'minwidth' => 10, 'minheight' => 10,
                        'maxwidth' => 5500, 'maxheight' => 5500));
                    $filename = $upload->getFilename();
                    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                    $filename = basename($filename);
                    $newfilename = mt_rand() . '.' . $ext;
                    $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $path . $newfilename, 'overwrite' => false)));
                    if (!$upload->isValid()) {
                        $this->_flashMessenger->addMessage(array('error' => 'O tamanho arquivo é muito grande.'));
                    }
                    if ($upload->receive()) {

                        //Para funcionar esse metodo $form->getValues(); precisa ficar abaixo do método $upload->receive()
                        $data = $form->getValues();
                        $data['banner_imagem'] = $newfilename;
                        // Qualquer Manipulação de Dados
                        $banners->insert($data);
                        $banners->getAdapter()->commit();
                        $form->reset(); //limpa os campos do form.
                        $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                    }
                } catch (Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_File_Transfer_Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_Db_Table_Exception $e) {
                    $banners->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            } else {
                $form->populate($data);
            }
        }
        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function editAction()
    {
        // Criação do Objeto Formulário
        $form = new Application_Form_Banner();
        $banners = new Application_Model_Banners();
        $form->getElement('banner_imagem')->setRequired(false);
        $form->getElement('banner_imagem')->setIgnore(true);

        $id = $this->_request->getParam('id');
        $data = $banners->find($id)->current()->toArray();
        $path = APPLICATION_PATH . '/../public/banners/';
        $banner_imagemdb = $data['banner_imagem'];
//        Zend_Debug::dump( $form->getValues());
        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();

//            $data = $form->getValues();
//            Zend_Debug::dump($this->getRequest()->getPost());
//            print($form->getValue('banner_imagem'));
//            $banner_imagem = $form->getValue('banner_imagem');


            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            if ($form->isValid($data)) {
//                Zend_Debug::dump($this->getRequest()->getPost());
                try {

                    // Dados Filtrados pelo Formulário
                    $banners->getAdapter()->beginTransaction();
//                    Zend_Debug::dump($banner_imagem);
                    if ($form->banner_imagem->isUploaded()) {
                        //gambi para renomear arquivos
                        $upload = new Zend_File_Transfer_Adapter_Http();
                        $upload->addValidator('Size', false, array('min' => 100,
                            'max' => 11150000,
                            'bytestring' => true));
                        $upload->addValidator('ImageSize', false, array(
                            'minwidth' => 10, 'minheight' => 10,
                            'maxwidth' => 5500, 'maxheight' => 5500));
                        $filename = $upload->getFilename();
                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                        $filename = basename($filename);
                        $newfilename = mt_rand() . '.' . $ext;
                        $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $path . $newfilename, 'overwrite' => false)));
                        if (!$upload->isValid()) {
                            $this->_flashMessenger->addMessage(array('error' => 'O tamanho arquivo é muito grande.'));
                        }
                        if ($upload->receive()) {
                            //apaga imagem antiga            
                            @unlink($path . $banner_imagemdb);

                            //Para funcionar esse metodo $form->getValues(); precisa ficar abaixo do método $upload->receive()
                            $data = $form->getValues();
                            $data['banner_imagem'] = $newfilename;
                            // Qualquer Manipulação de Dados
                            $where = $banners->getAdapter()->quoteInto("banner_id = ?", $id);
                            $banners->update($data, $where);
                            $banners->getAdapter()->commit();
                            $form->reset(); //limpa os campos do form.
                            $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                        }
                    } else {
                        $form->getElement('banner_imagem')->setRequired(false);
                        $form->getElement('banner_imagem')->setIgnore(true);
                        //Para funcionar esse metodo $form->getValues(); precisa ficar abaixo do método $upload->receive()
                        $data = $form->getValues();
                        $data['banner_imagem'] = $banner_imagemdb;
                        // Qualquer Manipulação de Dados
                        $where = $banners->getAdapter()->quoteInto("banner_id = ?", $id);
                        $banners->update($data, $where);
                        $banners->getAdapter()->commit();
                        $form->reset(); //limpa os campos do form.
                        $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                    }
                } catch (Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_File_Transfer_Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_Db_Table_Exception $e) {
                    $banners->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        }

//        Zend_Debug::dump($data);
        $form->populate($data);
        $imagePreview = $form->createElement('image', 'image_preview');
// element options
        $imagePreview->setLabel('Preview Image: ');
        $imagePreview->setAttrib('style', 'width:200px;height:auto;');
// add the element to the form
        $imagePreview->setOrder(4);
        $imagePreview->setImage('/public/banners/' . $data['banner_imagem']);

        $form->addElement($imagePreview);


        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function edit1Action()
    {
        $id = $this->_request->getParam('id');
        $itemPage = new CMS_Content_Item_Page($id);
        $pageForm = new Form_PageForm();
        $pageForm->setAction('/page/edit');
        if ($this->getRequest()->isPost()) {
            if ($pageForm->isValid($_POST)) {
                $itemPage->name = $pageForm->getValue('name');
                $itemPage->headline = $pageForm->getValue('headline');
                $itemPage->description = $pageForm->getValue('description');
                $itemPage->content = $pageForm->getValue('content');
                if ($pageForm->image->isUploaded()) {
                    $pageForm->image->receive();
                    $itemPage->image = '/images/upload/' .
                            basename($pageForm->image->getFileName());
                }
// save the content item
                $itemPage->save();
                return $this->_forward('list');
            }
        }
        $pageForm->populate($itemPage->toArray());
// create the image preview
        $imagePreview = $pageForm->createElement('image', 'image_preview');
// element options
        $imagePreview->setLabel('Preview Image: ');
        $imagePreview->setAttrib('style', 'width:200px;height:auto;');
// add the element to the form
        $imagePreview->setOrder(4);
        $imagePreview->setImage($itemPage->image);
        $pageForm->addElement($imagePreview);
        $this->view->form = $pageForm;
    }

    private function uploadImage()
    {

        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->setDestination(APPLICATION_ROOT . '/public/images')
                ->addValidator('Count', false, array('min' => 0, 'max' => 20));

        $this->uploadInfo['slide_id'] = $this->slideId;
        $this->uploadInfo['file_path'] = APPLICATION_ROOT . '/public/images';

        if (isset($_POST['caption'])) {
            $captions = $_POST['caption'];
        }
        if (isset($_POST['imageId'])) {
            $imageId = $_POST['imageId'];
        }

        $captionCount = 0;
        $files = $upload->getFileInfo();
        $fileName = '';

        foreach ($files as $file => $info) {
            //Check if this image to upload or to change 
            //here to change 
            if (isset($imageId[$captionCount])) {

                $this->updateImage($imageId[$captionCount], $captions[$captionCount]);

                //When try to print file name in the page the print success 
                echo 'file name1: ' . $info['name'] . '<br/>';

                if (isset($info['name']) && $info['name'] != null) {

                    //and here the print success 
                    echo 'file name2: ' . $info['name'] . '<br/>';

                    $fileName = $imageId[$captionCount] . '.' . $this->findexts($info['name']);

                    //and here 
                    echo 'file name2: ' . $fileName . '<br/>';

                    $upload->addFilter('Rename', array(
                        'target' => $fileName,
                        'overwrite' => true
                    ));

                    //Check is the upload file valid 

                    if (!$upload->isValid()) {
                        //If not valid assign error to view 
                        print_r($upload->getMessages());
                        $this->view->fileError = $upload->getMessages();
                    } else if (!$upload->receive($file)) {
                        //If can't upload this file assign error to view 
                        $this->view->fileError = $upload->getMessages();
                    } else {
                        //if file uploaded and valid add statement 
                        $this->view->success = 'The statement has been uploaded successfully. ';
                    }
                }
            } else {

                //and here to upload 
                $this->uploadInfo['caption'] = $captions[$captionCount];

                //echo $info['name'] . '<br/>'; 
                //$info['name'] = $this->addImage() . '.' . $this->findexts($info['name']); 
                echo 'file name3: ' . $info['name'] . '<br/>';
                $fileName = $this->addImage() . '.' . $this->findexts($info['name']);

                echo 'file name3: ' . $fileName . '<br/>';

                print_r($info);
                $upload->addFilter('Rename', array(
                    'target' => $fileName,
                    'overwrite' => true
                ));

                //Check is the upload file valid 
                if (!$upload->isValid()) {
                    //If not valid assign error to view 
                    print_r($upload->getMessages());
                    $this->view->fileError = $upload->getMessages();
                } else if (!$upload->receive($file)) {
                    //If can't upload this file assign error to view 
                    $this->view->fileError = $upload->getMessages();
                } else {
                    //if file uploaded and valid add statement 
                    $this->view->success = 'The statement has been uploaded successfully. ';
                }
            }

            $captionCount++;
        }
    }

}
