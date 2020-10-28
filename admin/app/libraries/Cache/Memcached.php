<?php

final class Cache_Memcached {

    private $_memcached = null;
    private $_memcached_enabled = null;
    private $_lkeydata = array();

    public function __construct() {
        $this->_memcached_enabled = extension_loaded('memcached');
        if ($this->_memcached_enabled) {
            $this->_memcached = new Memcached();
            $this->_memcached->addserver(key(unserialize(MC_SERVER)), current(unserialize(MC_SERVER)));
        }
    }

    public function set($key, $data, $ttl = 3600) {
        if ($this->_memcached_enabled) {
            return $this->_memcached->set(sha1($key), array($data, time(), $ttl), 0, $ttl);
        }
    }

    public function get($key) {
        return isset($this->_lkeydata[sha1($key)]) ? $this->_lkeydata[sha1($key)] : null;
    }

    public function valid($key) {
        if ($this->_memcached_enabled) {
            $data = $this->_memcached->get(sha1($key));
            if ($data) {
                $this->_lkeydata[sha1($key)] = (is_array($data)) ? $data[0] : false;
                return true;
            }
        }
        return false;
    }

}
