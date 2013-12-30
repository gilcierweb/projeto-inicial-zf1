<?php

class Adm_MarcasController extends Zend_Controller_Action {

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
        $marcas = new Application_Model_Marcas();
        // lista de marcas 
        $this->view->rows = $marcas->fetchAll();
    }

    public function addaAction() {

        $form = new DocumentForm();
        $this->view->form = $form;

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {

                /* Uploading Document File on Server */
                $upload = new Zend_File_Transfer_Adapter_Http();
                $upload->setDestination("/uploads/files/");
                try {
                    // upload received file(s)
                    $upload->receive();
                } catch (Zend_File_Transfer_Exception $e) {
                    $e->getMessage();
                }

                // so, Finally lets See the Data that we received on Form Submit
                $uploadedData = $form->getValues();
                Zend_Debug::dump($uploadedData, 'Form Data:');

                // you MUST use following functions for knowing about uploaded file 
                # Returns the file name for 'doc_path' named file element
                $name = $upload->getFileName('doc_path');

                # Returns the size for 'doc_path' named file element 
                # Switches of the SI notation to return plain numbers
                $upload->setOption(array('useByteString' => false));
                $size = $upload->getFileSize('doc_path');

                # Returns the mimetype for the 'doc_path' form element
                $mimeType = $upload->getMimeType('doc_path');

                // following lines are just for being sure that we got data
                print "Name of uploaded file: $name 
";
                print "File Size: $size 
";
                print "File's Mime Type: $mimeType";

                // New Code For Zend Framework :: Rename Uploaded File
                $renameFile = 'newName.jpg';

                $fullFilePath = '/images/' . $renameFile;

                // Rename uploaded file using Zend Framework
                $filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));

                $filterFileRename->filter($name);

                exit;
            }
        } else {

            // this line will be called if data was not submited
            $form->populate($formData);
        }
    }

    public function addAction() {
//        $util = new Mylib_Util();
//        echo $util->random_string('numeric', 2);
//        $string = "Here is a nice text string consisting of eleven words.";
//
//        echo $string = $util->word_limiter($string, 5);
//        echo '<br />';
//        echo $util->site_url('/news/local/123');
//        echo '<br />';
//        echo $util->anchor('/news/local/123', 'My News', array('title' => 'The best news!'));
//        $title = "What's wrong with CSS?";
//
//        ECHO $url_title = $util->url_title($title, '_', TRUE);
//
//        $atts = array(
//            'width' => '800',
//            'height' => '600',
//            'scrollbars' => 'yes',
//            'status' => 'yes',
//            'resizable' => 'yes',
//            'screenx' => '0',
//            'screeny' => '0'
//        );
//
//        echo $util->anchor_popup('/news/local/123', 'Click Me!', $atts);
//
//        $string = "Here is a nice text string consisting of eleven words.";
//
//        echo '<br>' . $string = $util->character_limiter($string, 20);
//
//        die;
        // Criação do Objeto Formulário
        $form = new Application_Form_Marca();
        $marcas = new Application_Model_Marcas();
        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();
            $path = APPLICATION_PATH . '/../public/marcas/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            if ($form->isValid($data)) {

                // Dados Filtrados pelo Formulário
                $marcas->getAdapter()->beginTransaction();
                //gambi para renomear arquivos
                $upload = new Zend_File_Transfer_Adapter_Http();
//                $upload->addValidator('Size', false, array('min' => 100,
//                    'max' => 10000,
//                    'bytestring' => true));
                $upload->addValidator('ImageSize', false, array('minwidth' => 10,
                    'minheight' => 10,
                    'maxwidth' => 1500,
                    'maxheight' => 1500));
                $filename = $upload->getFilename();
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                $filename = basename($filename);
                $newfilename = mt_rand() . '.' . $ext;
                $upload->addFilter(new Zend_Filter_File_Rename(array('target' => $path . $newfilename, 'overwrite' => false)));
                if (!$upload->isValid()) {
                    $this->_flashMessenger->addMessage(array('error' => '$e->getMessage()'));
                }
                try {

                    if ($upload->receive()) {

                        //Para funcionar esse metodo $form->getValues(); precisa ficar abaixo do método $upload->receive()
                        $data = $form->getValues();
                        $data['marca_imagem'] = $newfilename;
                        // Qualquer Manipulação de Dados
                        $marcas->insert($data);
                        $marcas->getAdapter()->commit();
                        $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                    }
                } catch (Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_File_Transfer_Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_Db_Table_Exception $e) {
                    $marcas->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            } else {
                $form->populate($data);
            }
        }
        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

    public function testeAction() {
        $form = new Application_Form_Teste();
        $request = $this->getRequest();
        $post = $request->getPost(); // This contains the POST params
        $path = APPLICATION_PATH . '/../public/marcas/';

        if ($request->isPost()) {
            if ($form->isValid($post)) {

                $upload = new Zend_File_Transfer_Adapter_Http();
                $filename = $upload->getFilename();
                $filename = basename($filename);


                $uniqueToken = mt_rand();
                $filterRename = new Zend_Filter_File_Rename(array('target' => $path . $uniqueToken . $filename, 'overwrite' => false));
                $upload->addFilter($filterRename);

                if (!$upload->receive()) {
                    $this->view->message = 'Error receiving the file';
                    return;
                }

                $this->view->message = 'Screenshot(s) successfully uploaded';
            }
        }

        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

//    public function uploadAction()
//    {
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            try {
//                $adapter = new Zend_File_Transfer_Adapter_Http();
//                $adapter->addValidator('Count', false, array('min' => 1, 'max' => 3))
//                        ->addValidator('Size', false, array('max' => 10000))
//                        ->addValidator('Extension', false, array('extension' => 'txt,sql', 'case' => true));
//                $adapter->setDestination("c:\web\tempfiles");
//                $files = $adapter->getFileInfo();
////print_r($files);
//                foreach ($files as $fieldname => $fileinfo) {
//                    if (($adapter->isUploaded($fileinfo[name])) && ($adapter->isValid($fileinfo['name']))) {
//                        $extension = substr($fileinfo['name'], strrpos($fileinfo['name'], '.') + 1);
//                        $filename = 'file_' . date('Ymdhs') . '.' . $extension;
//                        $adapter->addFilter('Rename', array('target' => 'c:\web\tempfiles\' . $filename,'overwrite' => true));
//                        $adapter->receive($fileinfo[name]);
////then, store in db..
//                    }
//                }
//                var_dump($adapter->getMessages());
//            } catch (Exception $ex) {
//                echo "Exception!\n";
//                echo $ex->getMessage();
//            }
//        }
//}

    public function editAction() {
        // Criação do Objeto Formulário
        $form = new Application_Form_Marca();
        $marcas = new Application_Model_Marcas();
        $form->getElement('marca_imagem')->setRequired(false);
        $form->getElement('marca_imagem')->setIgnore(true);

        $id = $this->_request->getParam('id');
        $data = $marcas->find($id)->current()->toArray();
        $path = APPLICATION_PATH . '/../public/marcas/';
        $marca_imagemdb = $data['marca_imagem'];

        // Há dados para Tratamento?
        if ($this->getRequest()->isPost()) {

            // Pegamos os Dados como Foram Enviados
            $data = $this->getRequest()->getPost();

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            if ($form->isValid($data)) {
//                Zend_Debug::dump($this->getRequest()->getPost());
                try {

                    // Dados Filtrados pelo Formulário
                    $marcas->getAdapter()->beginTransaction();
//                    Zend_Debug::dump($marca_imagem);
                    if ($form->marca_imagem->isUploaded()) {
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
                            @unlink($path . $marca_imagemdb);

                            //Para funcionar esse metodo $form->getValues(); precisa ficar abaixo do método $upload->receive()
                            $data = $form->getValues();
                            $data['marca_imagem'] = $newfilename;
                            // Qualquer Manipulação de Dados
                            $where = $marcas->getAdapter()->quoteInto("marca_id = ?", $id);
                            $marcas->update($data, $where);
                            $marcas->getAdapter()->commit();
                            $form->reset(); //limpa os campos do form.
                            $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                        }
                    } else {
                        $form->getElement('marca_imagem')->setRequired(false);
                        $form->getElement('marca_imagem')->setIgnore(true);
                        //Para funcionar esse metodo $form->getValues(); precisa ficar abaixo do método $upload->receive()
                        $data = $form->getValues();
                        $data['marca_imagem'] = $marca_imagemdb;
                        // Qualquer Manipulação de Dados
                        $where = $marcas->getAdapter()->quoteInto("marca_id = ?", $id);
                        $marcas->update($data, $where);
                        $marcas->getAdapter()->commit();
                        $form->reset(); //limpa os campos do form.
                        $this->_flashMessenger->addMessage(array('success' => 'Dados salvos com sucesso!'));
                    }
                } catch (Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_File_Transfer_Exception $e) {
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                } catch (Zend_Db_Table_Exception $e) {
                    $marcas->getAdapter()->rollBack();
                    $this->_flashMessenger->addMessage(array('error' => $e->getMessage()));
                }
            }
        }

        $form->populate($data);
        $imagePreview = $form->createElement('image', 'image_preview');
// element options
        $imagePreview->setLabel('Preview Image: ');
        $imagePreview->setAttrib('style', 'width:200px;height:auto;');
// add the element to the form
        $imagePreview->setOrder(4);
        $imagePreview->setImage('/public/marcas/' . $data['marca_imagem']);

        $form->addElement($imagePreview);

        // Envio para a Camada de Visualização
        $this->view->form = $form;
    }

}
