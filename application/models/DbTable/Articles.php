<?php

class Model_DbTable_Articles extends Zend_Db_Table_Abstract {

    protected $_name = 'article';

    public function getArticles($id) {
        $result = $this->fetchAll();
        if (!$result) {
            return false;
        }
        return $result->toArray();
    }

    public function getArticle($id) {
        $id = (int) $id;
        $row = $this->fetchRow('cat_id = ' . $id);
        if (!$row) {
            return false;
        }
        return $row->toArray();
    }

    public function getArticleById($id) {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            return false;
        }
        return $row->toArray();
    }

    /*
     * Add new posts
     */

    public function saveArticle($post) {
        $data = array('id' => '',
            'article_name' => $post['article'],
            'cat_id' => $post['category'], //$post['cat_id'],
            'section_id' => $post['section'], //$post['section_id'],
            'content' => isset($post['content']) && ($post['content']) ? $post['content'] : '',
            'files' => isset($post['files']) && ($post['files']) ? $post['files'] : 0,
            'date' => time(),
            'article_name_danish' => isset($post['article_name_danish']) && ($post['article_name_danish']) ? $post['article_name_danish'] : NULL,
            'content_danish' => isset($post['content_danish']) && ($post['content_danish']) ? $post['content_danish'] : NULL
        );
        $this->insert($data);
    }

    /*
     * Update old posts
     */

    public function updateArticle($article) {
        $content_danish = array();
        $article_name_danish = array();
        if (isset($article['article_name_danish']) && ($article['article_name_danish'])) {
            $data['article_name_danish'] = $article['article_name_danish'];
//$article_name_danish.=$article_name_danish.',';
        }
        if (isset($article['content_danish']) && ($article['content_danish'])) {
            $data['content_danish'] = $article['content_danish'];
        }

        $data = array('article_name' => $article['article_name'],
            'cat_id' => $article['cat_id'],
            'section_id' => $article['section_id'],
            'content' => isset($article['content']) && ($article['content']) ? $article['content'] : '',
            'files' => isset($article['files']) && ($article['files']) ? $article['files'] : '',
            'date' => time()
        );
        $where = 'id = ' . $article['article_id'];
        $this->update($data, $where);
    }

    public function getArticleSearch($key_word=NULL) {

        $select = $this->select()->setIntegrityCheck(false)
                        ->from('article')
                        ->joinLeft('category', 'article.cat_id=category.id', array('category.name as cat_name'))
                        ->joinLeft('section', 'article.section_id=section.id', array('section.name as sec_name'))
                        ->where("article.article_name LIKE '%$key_word%'")
                        ->orwhere("article.content LIKE '%$key_word%'");
        $result = $this->fetchAll($select);
        if (!$result) {
            return false;
        }
        return $result->toArray();
    }

    public function getAllArticle() {
        $select = $this->select()->setIntegrityCheck(false)
                        ->from('article')
                        ->joinLeft('category', 'article.cat_id=category.id', array('category.name as cat_name'))
                        ->joinLeft('section', 'article.section_id=section.id', array('section.name as sec_name'))
                        ->order('article_name ASC');
        $result = $this->fetchAll($select);
        if (!$result) {
            return false;
        }
        return $result->toArray();
    }

    public function getNumOfArticle($section_id, $cat_id) {
        $select = $this->select()
                        ->from('article', array('COUNT(*)'))->where("section_id='$section_id'")
                        ->where("cat_id ='$cat_id'");
        $result = $this->fetchRow($select);

        //$result = $this->fetchAll("name LIKE '%$key_word%' || content LIKE '%$key_word%'");
        if (!$result) {
            return false;
        }
        $row = $result->toArray();
        return $row['COUNT(*)'];
    }

    public function deleteArticle($id=NULL) {
        $where = 'id = ' . $id;
        $this->delete($where);
    }

    public function deleteArticleByCategoryId($id=NULL) {
        $where = 'cat_id = ' . $id;
        $this->delete($where);
    }

}
