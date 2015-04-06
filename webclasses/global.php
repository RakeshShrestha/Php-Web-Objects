<?php

date_default_timezone_set(DEFAULT_TIMEZONE);

spl_autoload_extensions('.php');
spl_autoload_register(array('Autoloader', 'load'));

class Autoloader {

    public static function load($class) {
        if (is_readable(CLASS_PATH . 'controllers/' . $class . '.php')) {
            require_once CLASS_PATH . 'controllers/' . $class . '.php';
        } elseif (is_readable(CLASS_PATH . 'lib/' . str_replace('.', '/', $class) . '.php')) {
            require_once CLASS_PATH . 'lib/' . $class . '.php';
        }
        if (!class_exists($class, false)) {
            throw new ClassNotFoundException('class: 404 - ' . $class . ' not declared');
        }
    }

}

class RequestException extends Exception {
    
}

class ClassNotFoundException extends Exception {
    
}

class LibraryNotFoundException extends Exception {
    
}

class ViewNotFoundException extends Exception {
    
}

class FileIOException extends Exception {
    
}

class DatabaseException extends Exception {
    
}
