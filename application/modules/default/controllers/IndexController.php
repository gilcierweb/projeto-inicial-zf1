<?php

class Default_IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->_helper->layout->setLayout('/layout_site');
    }

    public function indexAction()
    {
        $produtos = new Application_Model_Produtos();
        // lista de produtos     
        $this->view->rows = $produtos->fetchAll();
    }


}

