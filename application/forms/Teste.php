<?php

class Application_Form_Teste extends Zend_Form
{

    public function init()
    {
      $file = new Zend_Form_Element_File('screenshot');
//$file->setLabel(null)->setDestination(APPLICATION_PATH . '/path/to/uploads');
$file->addValidator('Count', false, 5);
$file->addValidator('Extension', false, 'jpg,png,gif');
$file->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Upload File');
        
        $this->addElements(
                array(
                    $file, $submit
                )
        )
        ;
    }

}
