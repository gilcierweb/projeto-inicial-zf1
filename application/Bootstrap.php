<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initApplication()
    {
        $this->bootstrap('frontcontroller');
        $front = $this->getResource('frontcontroller');
        $front->addModuleDirectory(dirname(__FILE__) . '/modules');
    }

    public function _initAutoload() {
      Zend_Loader_Autoloader::getInstance()
                ->registerNamespace('Mylib')
                ->setFallbackAutoloader(true);
    }

}
