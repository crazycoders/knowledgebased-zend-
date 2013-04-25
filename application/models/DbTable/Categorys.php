<?php

class Model_DbTable_Categorys extends Zend_Db_Table_Abstract {

    protected $_name = 'category';

    public function getCategorys($id=NULL) {
        $result = $this->fetchAll("section_id = '$id'");
        if (!$result) {
            return false;
        }
        return $result->toArray();
    }

    public function getCategorysById($id=NULL) {
        $id = (int) $id;
        $row = $this->fetchRow('id = ' . $id);
        if (!$row) {
            return false;
        }
        return $row->toArray();
    }

    public function saveCategory($post) {
        $data = array('id' => '',
            'name' => $post['catrgory_name'],
            'section_id' => $post['section'],
            'name_danish' => isset($post['catrgory_name_danish']) && ($post['catrgory_name_danish']) ? $post['catrgory_name_danish'] : '');
        $this->insert($data);
        return $this->getAdapter()->lastInsertId();
    }

    public function updateCategory($category) {

        if (isset($category['category_name_danish']) && ($category['category_name_danish'])) {
            $data['name_danish'] = $category['category_name_danish'];
        }
        if (isset($category['category_name']) && ($category['category_name'])) {
            $data['name'] = $category['category_name'];
        }

        $data['section_id'] = $category['section_id'];

        $where = 'id = ' . $category['cat_id'];
        $this->update($data, $where);
    }

    public function getCategorysSearch($key_word=NULL) {
        $result = $this->fetchAll("name LIKE '%$key_word%'");
        if (!$result) {
            return false;
        }
        return $result->toArray();
    }

    public function getAllCategories() {
        $select = $this->select()->setIntegrityCheck(false)
                        ->from('category')
                        ->joinLeft('section', 'category.section_id=section.id', array('section.name as sec_name'))
                        ->order('category.name ASC');
        $result = $this->fetchAll($select);
        if (!$result) {
            return false;
        }
        return $result->toArray();
    }

    public function deleteCategory($id=NULL) {
        $where = 'id = ' . $id;
        $this->delete($where);
    }

}
