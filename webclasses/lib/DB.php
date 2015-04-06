<?php

class DB {

    private $_dbh = null;
    private $_stmt = null;
    private $_queryString = null;
    private static $_context = null;

    public static function getContext(array $config = array()) {
        if (self::$_context === null) {
            self::$_context = new self($config);
        }
        return self::$_context;
    }

    public function __construct(array $config = array()) {
        list($dbtype, $host, $user, $pass, $dbname) = $config;
        $dsn = $dbtype . ':host=' . $host . ';dbname=' . $dbname;
        $options = array(PDO::ATTR_PERSISTENT => true);
        try {
            $this->_dbh = new PDO($dsn, $user, $pass, $options);
            $this->_dbh->exec('SET NAMES utf8');
            $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            write_log('pdodatabase', $e->getMessage());
            exit();
        }
    }

    // table or view check for exist
    public function isTable($table = '') {
        try {
            $this->prepare("SHOW TABLES LIKE '" . strtolower($table) . "'");
            $this->execute();
            return $this->getRowCount();
        } catch (PDOException $e) {
            return false;
        }

        return false;
    }

    public function prepare($query) {
        try {
            $this->_queryString = $query;
            $this->_stmt = $this->_dbh->prepare($query);
            query_log($query);
        } catch (PDOException $e) {
            write_log('pdodatabase', $e->getMessage());
            exit;
        }
    }

    public function bindParam($key, $val) {
        if ($key) {
            return $this->_stmt->bindValue($key, $val, PDO::PARAM_INT);
        }
    }

    public function execute(array $execute = array()) {
        if (count($execute)) {
            return $this->_stmt->execute($execute);
        } else {
            return $this->_stmt->execute();
        }
    }

    public function resultset() {
        return $this->_stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetch() {
        return $this->_stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getRowCount() {
        return $this->_stmt->rowCount();
    }

    public function begin() {
        return $this->_dbh->beginTransaction();
    }

    public function rollback() {
        return $this->_dbh->rollBack();
    }

    public function commit() {
        return $this->_dbh->commit();
    }

    public function getAffectedRows() {
        return $this->_stmt->rowCount();
    }

    public function getInsertID() {
        return $this->_dbh->lastInsertId();
    }

    public function getQueryString() {
        return $this->_queryString;
    }

    function __destruct() {
        $this->_dbh = null;
    }

}