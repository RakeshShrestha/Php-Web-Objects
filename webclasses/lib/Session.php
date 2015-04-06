<?php

class Session {

    private static $_context = null;

    public static function getContext() {
        if (self::$_context === null) {
            self::$_context = new self;
        }
        return self::$_context;
    }

    public function __construct() {
        session_name(SESS_NAME);
        session_start();

        if (SESS_TIMEOUT) {
            $this->_verify_inactivity((int) SESS_TIMEOUT_VAL);
        }
    }

    public function __set($key, $value) {
        @$_SESSION[$key] = $value;
    }

    public function __get($key) {
        return @$_SESSION[$key];
    }

    public function getId() {
        return session_id();
    }

    public function delete($key) {
        unset($_SESSION[$key]);
    }

    public function destroy() {
        session_destroy();
    }

    private function _verify_inactivity($maxtime = 300) {
        if (!$this->activity_time) {
            $this->activity_time = time();
        }

        if ((time() - $this->activity_time) > $maxtime) {
            $this->destroy();
        } else {
            $this->activity_time = time();
        }
    }

}
