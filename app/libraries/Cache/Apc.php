<?php

final class Cache_Apc {

    private $_apcenabled = false;
    private $_lkeydata = array();

    public function __construct() {
        $this->_apcenabled = extension_loaded('apc');
    }

    public function set($key, $data, $ttl = 180) {
        if ($this->_apcenabled) {
            apc_store(sha1($key), $data, $ttl);
        }
    }

    public function get($key) {
        return isset($this->_lkeydata[sha1($key)]) ? $this->_lkeydata[sha1($key)] : null;
    }

    public function valid($key) {
        if ($this->_apcenabled) {
            $success = false;
            $data = @apc_fetch(sha1($key), $success);
            if ($success) {
                $this->_lkeydata[sha1($key)] = $data;
            }
            return $success;
        }
        return false;
    }

}
