<?php

class Model_DbTable_Sections extends Zend_Db_Table_Abstract {

    protected $_name = 'section';

    public function getSections() {
        $result = $this->fetchAll();
        if(!$result){
            return false;
        }
        return $result->toArray();
    }

    public function getSection($id) {
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

    public function saveSection($post=NULL) {
        $data = array('id' => '',
            'name' => $post['section']);
        $this->insert($data);
       return $this->getAdapter()->lastInsertId();
    }

    /*
     * Update old posts
     */

    public function updateSection($section_id) {
        $data = array(
            'id' => $post['id'],
            'Title' => $post['Title'],
            'Description' => $post['Description']);
        $where = 'id = ' . $post['id'];
        $this->update($data, $where);
    }

}
