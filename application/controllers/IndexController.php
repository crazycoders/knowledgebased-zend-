<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $this->_redirect('article/index/');
    }

    public function loginAction() {
        
        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_helper->layout()->disableLayout();
            $this->_redirect('article/viewarticle');
        }
        $form = new Form_LoginForm();
        $this->view->form = $form;
        $request = $this->getRequest();
        if ($request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $authAdapter = $this->getAuthAdapter();
                $username = $form->getValue("username");
                $password = $form->getValue("password");
                $authAdapter->setIdentity($username)
                        ->setCredential($password);
                $auth = Zend_Auth::getInstance();
                $result = $auth->authenticate($authAdapter);
                if ($result->isValid()) {
                    $this->_helper->layout()->disableLayout();
                    $identity = $authAdapter->getResultRowObject();
                    $authStorage = $auth->getStorage();
                    $authStorage->write($identity);
                    $this->_redirect('article/viewarticle');
                }
            }//else{
//            $this->view->errorMessage="Invalid Username/Password";
//        }
        }
    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect("index/login");
    }

    private function getAuthAdapter() {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter());
        $authAdapter->setTableName("user")
                ->setIdentityColumn("username")
                ->setCredentialColumn("password");
        return $authAdapter;
    }

}

