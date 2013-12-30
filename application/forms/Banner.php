<?php

class Application_Form_Banner extends Zend_Form
{

//    public $textimage = array(
//        'ViewHelper',
//        array('FormElements', array('tag' => 'img')),
//        'FormElements',
//        array(array('openerror' => 'HtmlTag'), array('tag' => 'td', 'openOnly' => true, 'placement' => Zend_Form_Decorator_Abstract::APPEND, 'width' => '37%')),
//        'Errors',
//        array(array('closeerror' => 'HtmlTag'), array('tag' => 'td', 'closeOnly' => true, 'placement' => Zend_Form_Decorator_Abstract::APPEND)),
//        array(array('elementImg' => 'HtmlTag'), array('tag' => 'img', 'class' => 'imgpos')),
//        array('HtmlTag', array('tag' => 'td', 'align' => 'left')),
//        array('Label', array('tag' => 'td')),
//        array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'valign' => 'top', 'align' => 'right')),
//    );
//    public $elementDecorators = array(
//        'ViewHelper',
//        'FormElements',
//        array(array('openerror' => 'HtmlTag'), array('tag' => 'td', 'openOnly' => true, 'placement' => Zend_Form_Decorator_Abstract::APPEND)),
//        'Errors',
//        array(array('closeerror' => 'HtmlTag'), array('tag' => 'td', 'closeOnly' => true, 'placement' => Zend_Form_Decorator_Abstract::APPEND)),
//        array('HtmlTag', array('tag' => 'td', 'class' => 'element', 'align' => 'left', 'colspan' => '2')),
//        array('Label', array('tag' => 'td')),
//        array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'valign' => 'top', 'align' => 'right')),
//    );
//    public $buttonDecorators = array(
//        'ViewHelper',
//        'Errors',
//        'FormElements',
//        array('HtmlTag', array('tag' => 'td', 'align' => 'left')),
//        array(array('row' => 'HtmlTag'), array('tag' => 'div')),
//    );
//    public $addButton = array(
//        'ViewHelper',
//        'Errors',
//        'FormElements',
//        array('HtmlTag', array('tag' => 'td', 'align' => 'right')),
//        array(array('row' => 'HtmlTag'), array('tag' => 'div')),
//    );
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
        $titulo = new Zend_Form_Element_Text('banner_titulo');
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
//        $image = new Zend_Form_Element_File('banner_imagem');
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
        $file = new Zend_Form_Element_File('banner_imagem');
        $file->setLabel('Banner')
//                ->setDestination(APPLICATION_PATH . '/../public/marcas')
                ->setRequired(true)
                ->setDecorators($this->buttonDecorators1);
//        $file->setValueDisabled(true);
//           $image = $this->createElement('file','image');
//            $image->setLabel('Product Image1 (jpg/tif/eps) ')
//                  ->setRequired(false)
////                  ->setDestination(APPLICATION_PATH . '/../public/marcas')
//                  ->addValidator('Count',false,1)
//                  ->addValidator('Size',false,'10MB')
//                  ->addValidator('Extension',false,'jpg,tif,eps')
//                 ->setDecorators($this->buttonDecorators1);
//            $this->addElement($image);


        $link = new Zend_Form_Element_Text('banner_link');
        //$nome->$this->elementDecorators;
        $link->setLabel('Link')
//                ->setRequired(false)
//                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe um Link')
                ->setDecorators($this->elementDecorators)
                ->setAttrib('class', 'span6');

        $submit = new Zend_Form_Element_Submit('Salvar', array(
            'ignore' => true,
            'class' => 'btn btn-primary'
        ));
        $submit->setDecorators($this->buttonDecorators);

        $this->addElements(
                array(
                    $titulo, $file, $link, $submit
                )
        );
    }

}
