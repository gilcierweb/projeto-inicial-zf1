<?php

class Application_Model_Galerias extends Zend_Db_Table_Abstract
{

    protected $_name = 'galerias';
    protected $_primary = 'galeria_id';
    protected $_dependentTables = array('Application_Model_Galeriasimagens');

}
