<?php

class Application_Form_Subcategoria extends Zend_Form
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
        $categorias = new Application_Model_Categorias();
        $categoria_id = new Zend_Form_Element_Select('categoria_id');
        $categoria_id->setLabel('Nome')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecorators)
                ->setBelongsTo('categorias')
        ;

        $categoria_id->addMultiOption(0, 'Please select...');
        foreach ($categorias->fetchAll() as $row) {
            $categoria_id->addMultiOption($row['categoria_id'], $row['categoria_nome']);
        }

//        $sport = new Zend_Form_Element_Select(
//                'sport', array(
//            'required' => true,
//            'multiOptions' => array('baseball' => 'Baseball', 'football' => 'Football')
//                )
//        );


        $nome = new Zend_Form_Element_Text('sub_cat_nome');
        //$nome->$this->elementDecorators;
        $nome->setLabel('Nome')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecorators);

        $descricao = new Zend_Form_Element_Textarea('sub_cat_descricao');
        $descricao->setLabel('Descrição')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Escreva uma mensagem')
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array('rows' => '10', 'class' => 'span12'));


        $submit = new Zend_Form_Element_Submit('Salvar', array(
            'ignore' => true,
            'class' => 'btn btn-primary'
        ));
        $submit->setDecorators($this->buttonDecorators);

        $this->addElements(
                array(
                    $categoria_id, $nome, $descricao, $submit
                )
        );
    }

}
