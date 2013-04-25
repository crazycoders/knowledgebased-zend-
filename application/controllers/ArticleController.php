<?php

class ArticleController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();
        $sec_result = NULL; //print_r($this->getRequest());die();
        $id = NULL;
        $id = (int) $request->getParam('id');

        $this->set_lang($request->getParam('lang'));

        $this->view->lang = $this->get_lang();
        $this->view->active = $id;
        $section = new Model_DbTable_Sections();
        $category = new Model_DbTable_Categorys();
        $article = new Model_DbTable_Articles();

        if ($id) {
            $sec_result['section'] = $section->getSection($id);
            $sec_result['section_all'] = $this->getsectionAction();
            $sec_result['category'] = $category->getCategorys($id);
            for ($i = 0; $i < count($sec_result['category']); $i++) {
                $sec_result['category'][$i]['article'][] = $article->getArticle($sec_result['category'][$i]['id']);
            }
        } else {
            $sec_result = $article->getAllArticle();
            $sec_result['section_all'] = $this->getsectionAction();
        }
        $this->view->article = $sec_result;
    }

    public function set_lang($lang=NULL) {
        $sess = new Zend_Session_Namespace('lang');
        if (isset($lang) && ($lang)) {
            $sess->lang = $lang;
            //$lang = $request->getParam('lang');
        }
        if (!$sess->lang)
            $sess->lang = 'eng';
        return true;
    }

    public function get_lang() {
        $sess = new Zend_Session_Namespace('lang');
        return $sess->lang;
    }

    public function viewAction() {
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();

        $this->set_lang($request->getParam('lang'));

        $this->view->lang = $this->get_lang();
        $id = (int) $request->getParam('id');
        $this->view->active = $id;
        $document = array();
        $section = new Model_DbTable_Sections();
        $sec_result['section'] = $section->getSection($id);
        $sec_result['section_all'] = $this->getsectionAction();
        $category = new Model_DbTable_Categorys();
        $sec_result['category'] = $category->getCategorysById($id);
        $article = new Model_DbTable_Articles();
        $sec_result['article'][] = $article->getArticle($sec_result['category']['id']);
        $this->view->article = $sec_result;
    }

    public function searchAction() {
        $search_result = array();
        $request = $this->getRequest();
        $key_word = $request->getParam('key_word');
        $article = new Model_DbTable_Articles();
        $search_result = $article->getArticleSearch($key_word);
        for ($i = 0; $i < count($search_result); $i++) {
            $search_result[$i]['date'] = date("j F,Y \at g:i", $search_result[$i]['date']);
        }
        echo json_encode($search_result);
        exit();
    }

    public function addAction() {
        $this->check_auth();
        $section = new Model_DbTable_Sections();
        $sec_result = $section->getSections();

        $this->view->sec_result = $sec_result;
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();

        if ($this->getRequest()->isPost()) {//print_r($request->getPost());die();
            $model_article = new Model_DbTable_Articles();
            $sec_id = $model_article->saveArticle($request->getPost());
            $this->_redirect('article/viewarticle');
        }
    }

    public function getcategoryAction() {
        $request = $this->getRequest();
        $category = new Model_DbTable_Categorys();
        $sec_result = $category->getCategorys($request->getParam("id"));
        if (is_array($sec_result))
            echo json_encode($sec_result);
        exit();
    }

    public function addcategoryAction() {
        $this->check_auth();
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();

        $section = new Model_DbTable_Sections();
        $sec_result = $section->getSections();

        $this->view->sec_result = $sec_result;

        if ($request->isPost()) {
            $model_cat = new Model_DbTable_Categorys();
            $model_cat->saveCategory($request->getPost());
            $this->_redirect('article/viewcategory');
        }
    }

    public function editAction() {
        $this->check_auth();
        $section = new Model_DbTable_Sections();
        $sec_result = $section->getSections();
        $this->view->sec_result = $sec_result;
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();
        $articleid = (int) $request->getParam('id');
        $model_article = new Model_DbTable_Articles();
        $model_cat = new Model_DbTable_Categorys();
        $model_sec = new Model_DbTable_Sections();

        if (preg_match('/[0-9]+/', $articleid)) {
            $article = $model_article->getArticleById($articleid);
            $category = $model_cat->getCategorysById($article['cat_id']);
            $section = $model_sec->getSection($article['section_id']);
        }
        $this->view->article = $article;
        $this->view->section = $section;
        $this->view->category = $category;
        if ($this->getRequest()->isPost()) {//print_r($this->getRequest()->getPost());die();
            $model_article->updateArticle($this->getRequest()->getPost());
            $this->_redirect('article/viewarticle');
        }
    }

    public function editcategoryAction() {
        $this->check_auth();
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();
        $category = NULL;
        $model_sec = new Model_DbTable_Sections();
        $sec_result = $model_sec->getSections();
        $this->view->sec_result = $sec_result;
        $request = $this->getRequest();
        $cat_id = (int) $request->getParam('id');
        $model_cat = new Model_DbTable_Categorys();

        if (preg_match('/[0-9]+/', $cat_id)) {
            $category = $model_cat->getCategorysById($cat_id);
            $section = $model_sec->getSection($category['section_id']);
        }
        $this->view->section = $section;
        $this->view->category = $category;
        if ($request->isPost()) {
            $model_cat->updateCategory($request->getPost());
            $this->_redirect('article/viewcategory');
        }
    }

    public function getsectionAction() {
        $section = new Model_DbTable_Sections();
        return $section->getSections();
    }

    public function viewcategoryAction() {
        $this->check_auth();
        $this->_helper->layout()->disableLayout();
        $category = new Model_DbTable_Categorys();
        $sec_result = $category->getAllCategories();
        $article = new Model_DbTable_Articles();
        for ($i = 0; $i < count($sec_result); $i++) {
            $sec_result[$i]['num_of_article'] = $article->getNumOfArticle($sec_result[$i]['section_id'], $sec_result[$i]['id']);
        }
        $this->view->article = $sec_result;
    }

    public function viewarticleAction() {
        $this->check_auth();
        $this->_helper->layout()->disableLayout();
        $article = new Model_DbTable_Articles();
        $sec_result = $article->getAllArticle();
        $this->view->article = $sec_result;
    }

    public function check_auth() {
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('index/login');
            return false;
        }
        return true;
    }

    public function deletearticleAction() {
        $this->check_auth();
        $section = new Model_DbTable_Sections();
        $sec_result = $section->getSections();
        $this->view->sec_result = $sec_result;
        $this->_helper->layout()->disableLayout();
        $request = $this->getRequest();
        $articleid = (int) $request->getParam('id');
        if ($articleid) {
            $article = new Model_DbTable_Articles();
            $article->deleteArticle($articleid);
        }
        $this->_redirect('article/viewarticle');
    }

    public function deletecategoryAction() {
        $this->check_auth();
        $request = $this->getRequest();
        $categoryid = (int) $request->getParam('id');
        if ($categoryid) {
            $category = new Model_DbTable_Categorys();
            $category->deleteCategory($categoryid);

            $article = new Model_DbTable_Articles();
            $article->deleteArticleByCategoryId($categoryid);
        }
        $this->_redirect('article/viewcategory');
    }

}

