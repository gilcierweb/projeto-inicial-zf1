<?php

class Application_Form_Acl extends Zend_Form
{

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

    public function init()
    {
        $acl = new Application_Model_DbTable_Acl();
        $role = new Application_Model_DbTable_Role();
       
        $acl_id = new Zend_Form_Element_MultiCheckbox('acl_id');
        $acl_id->setLabel('acl')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecoratorsBoostrap)
                ->setBelongsTo('acl')
        ;

        foreach ($acl->fetchAll() as $row) {
            $acl_id->addMultiOption($row['id'], ' ' . $row['module'] . ' ' . $row['controller'] . ' ' . $row['action']);
        }


        $role_id = new Zend_Form_Element_Select('role_id');
        $role_id->setLabel('roles')
                ->setRequired(true)
                ->addValidator('NotEmpty')
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addErrorMessage('Informe o seu nome')
                ->setDecorators($this->elementDecoratorsBoostrap)
                ->setBelongsTo('role');

        $role_id->addMultiOption(0, 'Escolha...');
        foreach ($role->fetchAll() as $row) {
            $role_id->addMultiOption($row['id'], $row['role']);
        }


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
                    $acl_id, $role_id, $submit
                )
        );
        $this->setAttrib('class', 'form-horizontal');
    }

}
