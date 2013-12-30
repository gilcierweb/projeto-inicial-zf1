<?php

class Application_Form_Categoria extends Zend_Form
{

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
        $nome = new Zend_Form_Element_Text('categoria_nome');
        //$nome->$this->elementDecorators;
        $nome->setLabel('Nome')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecorators)
                ->setAttrib('class', 'span6');

        $descricao = new Zend_Form_Element_Textarea('categoria_descricao');
        $descricao->setLabel('Descrição')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Escreva uma mensagem')
                ->setDecorators($this->elementDecorators)
                ->setAttrib('class', 'span12')
                ->setAttrib('rows', '10');


        $submit = new Zend_Form_Element_Submit('Salvar', array(
            'ignore' => true,
             'class' => 'btn btn-primary'
        ));
        $submit->setDecorators($this->buttonDecorators);

        $this->addElements(
                array(
                    $nome, $descricao, $submit
                )
        )
        ;
    }

}
