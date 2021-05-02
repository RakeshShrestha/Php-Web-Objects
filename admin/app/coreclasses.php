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
require_once APP_DIR . 'config/config.php';
require_once APP_DIR . 'corefuncs.php';

unset($_REQUEST);
unset($_GET);

spl_autoload_extensions('.php');
spl_autoload_register(array(
    'Loader',
    'load'
));

class ApiException extends Exception
{
}

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
            throw $ex;
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

final class Request
{

    private $_pathprefix = null;

    private $_controller = null;

    private $_method = null;

    private static $_context = null;

    public static function getContext()
    {
        if (self::$_context === null) {
            self::$_context = new self();
        }

        return self::$_context;
    }

    public function isAjax()
    {
        if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) && mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            return true;
        }
        return false;
    }

    public function isPost()
    {
        return ($_SERVER['REQUEST_METHOD'] === 'POST');
    }

    public function getPathPrefix()
    {
        return $this->_pathprefix;
    }

    public function getController()
    {
        return mb_strtolower($this->_controller);
    }

    public function getMethod()
    {
        return mb_strtolower($this->_method);
    }

    public function getHeaders()
    {
        $headers = array();
        foreach ($_SERVER as $param => $value) {
            if (mb_strpos($param, 'HTTP_') === 0) {
                $headers[mb_str_replace(' ', '-', mb_ucwords(mb_str_replace('_', ' ', mb_strtolower(mb_substr($param, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function getToken()
    {
        return isset($_SERVER['HTTP_X_TOKEN']) ? $_SERVER['HTTP_X_TOKEN'] : (isset($_COOKIE['Admin-Token']) ? $_COOKIE['Admin-Token'] : '');
    }

    public function setPathPrefix($pathprefix = null)
    {
        $this->_pathprefix = $pathprefix;
    }

    public function setController($controllername = null)
    {
        $this->_controller = mb_strtolower($controllername);
    }

    public function setMethod($methodname = null)
    {
        $this->_method = mb_strtolower($methodname);
    }
}

final class Response
{

    private static $_context = null;

    private $_statusCode = array(
        200 => "200 OK",
        301 => "301 Moved Permanently",
        302 => "302 Found",
        303 => "303 See Other",
        307 => "307 Temporary Redirect",
        400 => "400 Bad Request",
        403 => "403 Forbidden",
        404 => "404 Not Found",
        405 => "405 Method Not Allowed",
        500 => "500 Internal Server Error",
        503 => "503 Service Unavailable"
    );

    private $_page_messages = array(
        405 => "Try HTTP GET.",
        500 => "Something went horribly wrong."
    );

    public static function getContext()
    {
        if (self::$_context === null) {
            self::$_context = new self();
        }

        return self::$_context;
    }

    public function setStatus($status)
    {
        header("HTTP/1.0 " . $this->_get_status_message($status));
    }

    public function redirect($path = null, $alertmsg = null)
    {
        if ($alertmsg) {
            $this->addSplashMsg($alertmsg);
        }

        $redir = getUrl($path);

        header("Location: $redir");
        exit();
    }

    public function display(array &$data = array(), $viewname = null)
    {
        View::display($data, $viewname);
    }

    public function assign(array &$data = array(), $viewname = null)
    {
        return View::assign($data, $viewname);
    }

    public static function addSplashMsg($msg = null)
    {
        Session::getContext(SESS_TYPE)->set('splashmessage', $msg);
    }

    public static function getSplashMsg()
    {
        $sess = Session::getContext(SESS_TYPE);
        $msg = $sess->get('splashmessage');
        $sess->set('splashmessage', null);
        return $msg;
    }

    private function _get_status_message($code)
    {
        if (! isset($this->_statusCode[$code])) {
            return $this->_statusCode[500];
        }
        return $this->_statusCode[$code];
    }
}

final class View
{

    public static function assign(array &$vars = array(), $viewname = null)
    {
        $req = req();
        if (is_array($vars)) {
            extract($vars);
        }
        ob_start();
        if ($viewname == null) {
            $viewname = mb_strtolower($req->getController() . '/' . $req->getMethod());
        }
        include VIEW_DIR . mb_strtolower($viewname) . '.php';
        return ob_get_clean();
    }

    public static function display(array &$vars = array(), $viewname = null)
    {
        $req = req();
        if ($viewname == null) {
            $viewname = mb_strtolower($req->getController() . '/' . $req->getMethod());
        }

        if (! isset($vars['layout'])) {
            $playout = 'layouts/' . $req->getPathPrefix() . 'layout';
            $vars['mainregion'] = self::assign($vars, $viewname);
        } else {
            if ($vars['layout']) {
                $playout = $vars['layout'];
            } else {
                $playout = $viewname;
            }
        }

        if (is_array($vars)) {
            extract($vars);
        }
        include VIEW_DIR . mb_strtolower($playout) . '.php';
    }
}

final class Application
{

    public static function run(Request &$request, Response &$response)
    {
        $uriparts = explode('/', mb_str_replace(SITE_URI . PATH_URI, '', SITE_URI . $_SERVER['REQUEST_URI']));
        $uriparts = array_filter($uriparts);

        $controller = ($c = array_shift($uriparts)) ? $c : MAIN_CONTROLLER;
        $pathprefix = '';

        if (in_array($controller, unserialize(PATH_PREFIX))) {
            $pathprefix = mb_strtolower($controller) . '_';
            $controller = ($c = array_shift($uriparts)) ? $c : MAIN_CONTROLLER;
        }

        $controllerfile = CONT_DIR . mb_strtolower($controller) . '.php';
        if (! preg_match('#^[A-Za-z0-9_-]+$#', $controller) || ! is_readable($controllerfile)) {
            $controller = MAIN_CONTROLLER;
            $controllerfile = CONT_DIR . MAIN_CONTROLLER . '.php';
        }

        $cont = 'c' . $controller;
        $method = ($c = array_shift($uriparts)) ? $pathprefix . mb_str_replace(unserialize(PATH_PREFIX), '', $c) : $pathprefix . MAIN_METHOD;
        $args = (isset($uriparts[0])) ? $uriparts : array();

        require_once $controllerfile;

        if (! method_exists($cont, $method)) {
            $method = MAIN_METHOD;
        }

        $request->setPathPrefix(mb_substr($pathprefix, 0, - 1));
        $request->setController($controller);
        $request->setMethod($method);

        $cont = new $cont();

        call_user_func_array(array(
            $cont,
            $method
        ), $args);
    }
}

final class Loader
{

    public static function load($classname)
    {
        $a = $classname[0];

        if ($a == 'c') {
            require_once CONT_DIR . mb_strtolower(mb_substr($classname, 1)) . '.php';
        } elseif ($a >= 'A' && $a <= 'Z') {
            require_once LIBS_DIR . mb_str_replace(array(
                '\\',
                '_'
            ), '/', $classname) . '.php';
        } else {
            require_once MODS_DIR . mb_strtolower($classname) . '.php';
        }
    }
}
