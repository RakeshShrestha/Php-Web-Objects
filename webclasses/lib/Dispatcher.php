<?php

class Dispatcher {

    public static function process(Request $request) {
        $uriparts1 = explode('/', str_replace(SITE_PATH, '-', $_SERVER['REQUEST_URI']));
        $uriparts = array_filter($uriparts1);
        array_shift($uriparts);
        array_walk($uriparts, 'do_secure_uri');

        //$request->setLang('en');

        $controller = ($c = array_shift($uriparts)) ? $c : 'Home';
        $pathfrefix = '';
        
        if (in_array($controller, unserialize(PATH_PREFIX))) {
            $pathfrefix = strtolower($controller) . '_';
            $request->setPathPrefix($controller);
            $controller = ($c = array_shift($uriparts)) ? $c : 'Home';
        }
        
        $method = ($c = array_shift($uriparts)) ? $pathfrefix . '' . $c : $pathfrefix . 'index';
        $args = (isset($uriparts[0])) ? $uriparts : array();

        try {
            if (is_callable(array($controller, $method))) {
                
            } else {
                write_log('classnotfound', $controller . '/' . $method . ' not declared and default method called');
                $method = 'index';
            }
            $request->setController($controller);
            $request->setMethod($method);

            $cont = new $controller;

            if (!empty($args)) {
                $request->setArgs($args);
                call_user_func_array(array($cont, $method), $args);
            } else {
                call_user_func(array($cont, $method));
            }
        } catch (ClassNotFoundException $e) {
            $controller = 'Home';
            $method = 'index';

            $request->setController($controller);
            $request->setMethod($method);

            write_log('classnotfound', $e->getMessage() . ' and default controller called');

            $cont = new $controller;

            call_user_func(array($cont, $method));
        }
    }

}
