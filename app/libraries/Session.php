<?php

final class Session {

    private static $_context = null;

    public static function getContext($sesstype) {
        if (self::$_context === null) {
            $classname = 'Session_' . $sesstype;
            self::$_context = new $classname;
        }

        return self::$_context;
    }

}
