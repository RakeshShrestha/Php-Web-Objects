<?php
/**
# Copyright Rakesh Shrestha (rakesh.shrestha@gmail.com)
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
#
# Redistributions must retain the above copyright notice.
*/

final class DB
{

    private static $_context = null;

    public static function getContext()
    {
        if (self::$_context) {
            return self::$_context;
        }

        list ($dbtype, $host, $user, $pass, $dbname) = unserialize(DB_CON);

        $dsn = $dbtype . ':host=' . $host . ';dbname=' . $dbname;

        try {
            self::$_context = new PDO($dsn, $user, $pass);
            self::$_context->exec('SET NAMES utf8');
            self::$_context->setAttribute(PDO::ATTR_PERSISTENT, true);
            self::$_context->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            self::$_context->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$_context->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        } catch (PDOException $ex) {
            echo $ex;
            exit();
            throw $ex;
        }

        return self::$_context;
    }
}

final class Session
{

    private static $_context = null;

    public static function getContext($sesstype)
    {
        if (self::$_context === null) {
            $classname = 'Session_' . $sesstype;
            self::$_context = new $classname();
        }

        return self::$_context;
    }
}

final class Cache
{

    private static $_context = null;

    public static function getContext($cachetype)
    {
        if (self::$_context === null) {
            $classname = 'Cache_' . $cachetype;
            self::$_context = new $classname();
        }

        return self::$_context;
    }
}
