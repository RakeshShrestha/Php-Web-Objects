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
