<?php

class Application_Form_Produtosimagens extends Zend_Form
{

    protected $elemDecorator = array(
        'Errors',
        'ViewHelper',
        array(array('wrapperField' => 'HtmlTag'), array('tag' => 'div', 'class' => 'input')),
        array('Label', array('placement' => 'prepend')),
        array(array('wrapperAll' => 'HtmlTag'), array('tag' => 'div', 'class' => 'clearfix')),
    );
    public $elementDecorators = array(
        'ViewHelper',
        'Errors',
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
        $produtos = new Application_Model_Produtos();

        $produto_id = new Zend_Form_Element_Select('produto_id');
        $produto_id->setLabel('Produtos')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecorators)
                ->setBelongsTo('produtos')
        ;

        $produto_id->addMultiOption(0, 'Please select...');
        foreach ($produtos->fetchAll() as $row) {
            $produto_id->addMultiOption($row['produto_id'], $row['produto_titulo']);
        }

        // create new element
        $image = new Zend_Form_Element_File('prod_img_imagem');
// element options
        $image->setLabel('Image: ')
                ->setIsArray(true)
                ->setMultiFile(2)
                ;
        $image->setRequired(TRUE);
//                $image->setDecorators($this->elemDecorator);
// DON’T FORGET TO CREATE THIS FOLDER
        $image->setDestination(APPLICATION_PATH . '/../public/images/upload');
// ensure only 1 file
        $image->addValidator('Count', false, 2);
// limit to 100K
        $image->addValidator('Size', false, 15102400);
// only JPEG, PNG, and GIFs
        $image->addValidator('Extension', false, 'jpg,png,gif');
// add the element to the form
        $this->addElement($image);
//        $file = new Zend_Form_Element_File('prod_img_imagem');
//        $file->setLabel('Imagem');
////                ->setRequired(true)
//                ->addValidator('NotEmpty')
//                ->addFilter('StripTags')
//                ->addFilter('StringTrim')
//                ->addErrorMessage('Informe o seu nome')
//                ->setDecorators($this->elementDecorators);

        $submit = new Zend_Form_Element_Submit('Salvar', array(
            'ignore' => true,
            'class' => 'btn btn-primary'
        ));
        $submit->setDecorators($this->buttonDecorators);

        $this->addElements(
                array(
                    $produto_id, $image, $submit
                )
        );
    }

}
