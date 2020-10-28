<?php

final class Session_Native {

    public function __construct() {
        @session_start();

        if (SESS_TIMEOUT)
            $this->_verifyInactivity(SESS_TIMEOUT);
    }

    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    public function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : '';
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

    private function _verifyInactivity($maxtime) {
        if (!$this->get('activity_time')) {
            $this->set('activity_time', time());
        }

        if ((time() - $this->get('activity_time')) > $maxtime) {
            $this->destroy();
        } else {
            $this->set('activity_time', time());
        }
    }

}