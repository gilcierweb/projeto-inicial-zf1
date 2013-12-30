<?php

class Application_Form_Login extends Zend_Form
{

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

    public function init()
    {
        $usuario = new Zend_Form_Element_Text('login');
        $usuario->setLabel('Login')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecorators);

        $senha = new Zend_Form_Element_Password('password');
        $senha->setLabel('Senha')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecorators);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Logar');
        $submit->setAttrib('id', 'submitbutton')
                ->setAttrib('class', 'btn btn-primary');

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
