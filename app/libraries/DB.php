<?php

final class DB {

    private static $_context = null;

    public static function getContext() {
        if (self :: $_context) {
            return self :: $_context;
        }

        list($dbtype, $host, $user, $pass, $dbname) = unserialize(DB_CON);

        $dsn = $dbtype . ':host=' . $host . ';dbname=' . $dbname;

        try {
            self :: $_context = new PDO($dsn, $user, $pass);
            self :: $_context->exec('SET NAMES utf8');
            self :: $_context->setAttribute(PDO::ATTR_PERSISTENT, true);
            self :: $_context->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            self :: $_context->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self :: $_context->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        } catch (PDOException $ex) {
            throw $ex;
        }

        return self :: $_context;
    }

}
