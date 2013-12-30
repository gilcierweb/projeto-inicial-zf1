<?php

class Application_Form_Galeria extends Zend_Form
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
        $marcas = new Application_Model_Marcas();

        $marca_id = new Zend_Form_Element_Select('marca_id');
        $marca_id->setLabel('Marca d\'água')
//                ->setRequired(true)
//                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecorators)
                ->setBelongsTo('marcas')
        ;

        $marca_id->addMultiOption(0, 'Escolha...');
        foreach ($marcas->fetchAll() as $row) {
            $marca_id->addMultiOption($row['marca_id'], $row['marca_titulo']);
        }

        $titulo = new Zend_Form_Element_Text('galeria_titulo');
        $titulo->setLabel('Nome')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecorators);

        $descricao = new Zend_Form_Element_Textarea('galeria_descricao');
        $descricao->setLabel('Descrição')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Escreva uma descrição')
                ->setDecorators($this->elementDecorators)
                ->setAttribs(array('rows' => '10', 'class' => 'span12'));

        $galeria_data = new Zend_Form_Element_Text('galeria_data');
        $galeria_data->setLabel('Data do evento')
//                ->setRequired(true)
//                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe a data do evento')
                ->setDecorators($this->elementDecorators)
                ->setAttrib('class', 'span3');

        $submit = new Zend_Form_Element_Submit('Salvar', array(
            'ignore' => true,
            'class' => 'btn btn-primary'
        ));
        $submit->setDecorators($this->buttonDecorators);

        $this->addElements(
                array(
                    $marca_id, $titulo, $descricao, $galeria_data, $submit
                )
        );
    }

}
