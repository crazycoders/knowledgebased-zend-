<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                    'namespace' => '',
                    'basePath' => APPLICATION_PATH));
        // print_r($moduleLoader);die();
        return $moduleLoader;
    }

    public function _initViewHelpers() {

        $this->bootstrap("layout");
        $layout = $this->getResource("layout");
        $view = $layout->getView();
//        $view->doctype('HTML4_STRICT');
//        $view->headMeta()->appendHttpEquiv('content-type','text/html')
//                ->appendName('description','using view');
        
    }

}

