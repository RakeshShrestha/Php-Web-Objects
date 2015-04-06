<?php

import('cache.FileCache');

class Model {

    private $_db = null;
    private $_cache = null;
    private $_table = '';
    private $_lastinsertid = 0;

    public function __construct($table = '', DB $db = null) {
        if ($db == null) {
            $this->_db = DB::getContext(unserialize(DB_CON));
        } else {
            $this->_db = $db;
        }

        if ($table !== '' and $this->_db->isTable($table)) {
            $this->_table = strtolower($table);
        } else {
            throw new DatabaseException("Table doesnot exist : $table");
        }

        $this->_cache = new FileCache;
    }

    public function getDbHandle() {
        return $this->_db;
    }

    public function select(array $selschema = array(), $expiryval = 60) {
        $execparam = array();
        $limitparam = array();
        $sqlquery = 'SELECT ';
        $sqlquery .= isset($selschema['fields']) ? $selschema['fields'] : '*';
        $sqlquery .= ' FROM ' . $this->_table;
        $sqlquery .= ' LIMIT :offset,:limit ';
        $limitparam[':offset'] = (isset($selschema['offset']) ? $selschema['offset'] : 0);
        $limitparam[':limit'] = (isset($selschema['limit']) ? $selschema['limit'] : 1000);

        if ($expiryval > 0 and $this->_cache->valid($sqlquery)) {
            return $this->_cache->get($sqlquery);
        } else {
            $this->_db->prepare($sqlquery);

            foreach ($limitparam AS $key => $val) {
                $this->_db->bindParam($key, $val);
            }
            $this->_db->execute($execparam);

            $result = $this->_db->resultset();

            if ($expiryval > 0) {
                $this->_cache->set($sqlquery, $result, $expiryval);
            }

            return $result;
        }
    }

    public function save(array $data = array()) {
        if (isset($data['id']) and $data['id'] > 0) {
            $id = $data['id'];
            unset($data['id']);
            return $this->_update($data, (int) $id);
        } else {
            $this->_insert($data);
            return $this->_lastinsertid;
        }
    }

    public function delete($id = 0) {
        if ((int) $id > 0) {
            $sql = 'DELETE FROM ' . $this->_table . ' WHERE id = :id';
            $this->_db->prepare($sql);
            $this->_db->execute(array(':id' => $id));
            return $this->_db->getAffectedRows();
        }
        return false;
    }

    private function _insert(array $data = array()) {
        if (empty($data)) {
            return false;
        }

        $execparam = array();

        $insert_fieldnames = "";
        $insert_paramnames = "";

        $count = 0;
        foreach ($data as $field => $value) {
            $insert_fieldnames .= "$field";
            $insert_paramnames .= ":$field";
            if ($count < count($data) - 1) {
                $insert_fieldnames .= ", ";
                $insert_paramnames .= ", ";
            }
            $count++;

            $execparam[':' . $field] = $value;
        }

        $sql = "INSERT INTO $this->_table ($insert_fieldnames) VALUE ($insert_paramnames)";
        $this->_db->prepare($sql);
        $this->_db->execute($execparam);
        $this->_lastinsertid = $this->_db->getInsertId();
    }

    private function _update(array $data = array(), $id = 0) {
        if (!$id || empty($data)) {
            return false;
        }

        $sql = 'UPDATE ' . $this->_table . ' SET ';

        $execparam = array();

        $count = 0;
        foreach ($data as $field => $value) {
            $sql .= "$field = :$field";
            if ($count < count($data) - 1) {
                $sql .= ", ";
            }
            $count++;
            $execparam[':' . $field] = $value;
        }

        $sql .= " WHERE id= :id";
        $execparam[':id'] = $id;

        $this->_db->prepare($sql);
        $this->_db->execute($execparam);
        return $this->_db->getAffectedRows();
    }

}

