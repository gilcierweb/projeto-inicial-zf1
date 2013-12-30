<?php

class Application_Model_Produtos extends Zend_Db_Table_Abstract {

    protected $_name = 'produtos';
    protected $_primary = 'produto_id';
    protected $_dependentTables = array('Application_Model_Produtosimagens');

   

}
