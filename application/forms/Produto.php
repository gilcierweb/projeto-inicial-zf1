<?php

class Application_Form_Produto extends Zend_Form {

    public $elementDecoratorsBoostrap = array(
        'ViewHelper',
        'Description',
        'Errors',
        array(array('data' => 'HtmlTag'), array('tag' => 'div',
                'class' => 'controls')),
        array('Label', array('requiredSuffix' => ' *', 'class' => 'control-label')),
        array(array('group' => 'HtmlTag'), array('tag' => 'div',
                'class' => 'control-group'))
    );
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

    public function init() {
        $categorias = new Application_Model_Categorias();
        $subcategorias = new Application_Model_Subcategorias();

        $categoria_id = new Zend_Form_Element_Select('categoria_id');
        $categoria_id->setLabel('Categorias')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecoratorsBoostrap)
                ->setBelongsTo('categorias')
        ;

        $categoria_id->addMultiOption(0, 'Please select...');
        foreach ($categorias->fetchAll() as $row) {
            $categoria_id->addMultiOption($row['categoria_id'], $row['categoria_nome']);
        }


        $sub_cat_id = new Zend_Form_Element_Select('sub_cat_id');
        $sub_cat_id->setLabel('Subcategorias')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecoratorsBoostrap)
                ->setBelongsTo('subcategorias');

        $sub_cat_id->addMultiOption(0, 'Please select...');
        foreach ($subcategorias->fetchAll() as $row) {
            $sub_cat_id->addMultiOption($row['sub_cat_id'], $row['sub_cat_nome']);
        }

        $titulo = new Zend_Form_Element_Text('produto_titulo');
        $titulo->setLabel('Nome')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecoratorsBoostrap);

        $resumo = new Zend_Form_Element_Textarea('produto_resumo');
        $resumo->setLabel('Resumo')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Escreva uma mensagem')
                ->setDecorators($this->elementDecoratorsBoostrap)
                ->setAttribs(array('rows' => '10', 'class' => 'span12'));

        $descricao = new Zend_Form_Element_Textarea('produto_descricao');
        $descricao->setLabel('Descrição')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Escreva uma mensagem')
                ->setDecorators($this->elementDecoratorsBoostrap)
                ->setAttribs(array('rows' => '10', 'class' => 'span12'));

        $preco = new Zend_Form_Element_Text('produto_preco');
        $preco->setLabel('Preço')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecoratorsBoostrap)
                ->setAttrib('class', 'span2');

        $submit = new Zend_Form_Element_Submit('Salvar', array(
            'ignore' => true,
            'class' => 'btn btn-primary'
        ));
        $submit->setDecorators($this->buttonDecorators);
        
//        $reset = new Zend_Form_Element_Submit('Limpar', array(
//            'ignore' => true,
//            'class' => 'btn'
//        ));
        $submit->setDecorators($this->buttonDecorators);

        $this->addElements(
                array(
                    $categoria_id, $sub_cat_id, $titulo, $resumo, $descricao, $preco, $submit
                )
        );
        $this->setAttrib('class', 'form-horizontal');
    }

}

class Default_Form_Login extends Zend_Form {

    public $elementDecorators = array(
        'ViewHelper',
        'Description',
        'Errors',
        array(array('data' => 'HtmlTag'), array('tag' => 'div',
                'class' => 'controls')),
        array('Label', array('requiredSuffix' => ' *', 'class' => 'control-label')),
        array(array('group' => 'HtmlTag'), array('tag' => 'div',
                'class' => 'control-group'))
    );

    public function init() {
        $usuario = new Zend_Form_Element_Text('usuario');
        $usuario->setDecorators($this->elementDecorators);

        $senha = new Zend_Form_Element_Password('senha');
        $senha->setDecorators($this->elementDecorators);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Logar');
        $submit->setAttrib('id', 'submitbutton')
                ->setAttrib('class', 'btn-primary');

        // o que vai ter no form
        $this->addElements(
                array(
                    $usuario,
                    $senha,
                    $submit
                )
        );

        $this->setAttrib('class', 'form-horizontal');
    }

}
