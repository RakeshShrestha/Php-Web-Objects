<?php

final class Cache {

    private static $_context = null;

    public static function getContext($cachetype) {
        if (self::$_context === null) {
            $classname = 'Cache_' . $cachetype;
            self::$_context = new $classname;
        }

        return self::$_context;
    }

}

