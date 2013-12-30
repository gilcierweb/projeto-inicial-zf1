<?php

class Application_Form_Marca extends Zend_Form
{

    public $elementDecorators = array(
        'ViewHelper',
        'Errors',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'span7')),
        array('Label', array('tag' => 'div', 'class' => 'span7')),
    );
    public $buttonDecorators1 = array(
        'File',
        array('Description', array('tag' => '', 'escape' => false)),
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'span7')),
        array('Label', array('tag' => 'div', 'class' => 'span7')),
    );
    public $buttonDecorators = array(
        'ViewHelper',
        array(array('data' => 'HtmlTag'), array('tag' => 'div', 'class' => 'span7')),
        array(array('label' => 'HtmlTag'), array('tag' => 'div', 'class' => 'span7')),
//        array(array('row' => 'HtmlTag'), array('tag' => 'tr')),
    );

    public function init()
    {
        $titulo = new Zend_Form_Element_Text('marca_titulo');
        //$nome->$this->elementDecorators;
        $titulo->setLabel('Titulo')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe um titulo')
                ->setDecorators($this->elementDecorators)
                ->setAttrib('class', 'span6');

        // create new element
//        $image = new Zend_Form_Element_File('marca_imagem');
//// element options
//        $image->setLabel('Image: ')
//                ->setIsArray(true)
//                ->setMultiFile(2)
//        ;
//        $image->setRequired(TRUE);
////                $image->setDecorators($this->elemDecorator);
//// DON’T FORGET TO CREATE THIS FOLDER
//        $image->setDestination(APPLICATION_PATH . '/../public/marcas');
//// ensure only 1 file
//        $image->addValidator('Count', false, 2);
//// limit to 100K
//        $image->addValidator('Size', false, 15102400);
//// only JPEG, PNG, and GIFs
//        $image->addValidator('Extension', false, 'jpg,png,gif');
//// add the element to the form
////        $this->addElement($image);
//
        $file = new Zend_Form_Element_File('marca_imagem');
        $file->setLabel('File')
//                ->setDestination(APPLICATION_PATH . '/../public/marcas')
               ->setRequired(true)
                
                ->setDecorators($this->buttonDecorators1);
        $file->setValueDisabled(true);

//           $image = $this->createElement('file','image');
//            $image->setLabel('Product Image1 (jpg/tif/eps) ')
//                  ->setRequired(false)
////                  ->setDestination(APPLICATION_PATH . '/../public/marcas')
//                  ->addValidator('Count',false,1)
//                  ->addValidator('Size',false,'10MB')
//                  ->addValidator('Extension',false,'jpg,tif,eps')
//                 ->setDecorators($this->buttonDecorators1);
//            $this->addElement($image);

        $submit = new Zend_Form_Element_Submit('Salvar', array(
            'ignore' => true,
            'class' => 'btn btn-primary'
        ));
        $submit->setDecorators($this->buttonDecorators);

        $this->addElements(
                array(
                    $titulo, $file, $submit
                )
        )
        ;
    }

}
