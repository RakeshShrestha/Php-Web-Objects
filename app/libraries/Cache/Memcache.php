<?php

final class Cache_Memcache {

    private $_memcached = null;
    private $_memcache_enabled = null;
    private $_lkeydata = array();

    public function __construct() {
        $_memcache_enabled = extension_loaded('memcache');
        if ($_memcache_enabled) {
            $this->_memcached = new Memcache();
            $this->_memcached->addserver(key(unserialize(MC_SERVER)), current(unserialize(MC_SERVER)));
        }
    }

    public function set($key, $data, $ttl = 3600) {
        if ($_memcache_enabled) {
            return $this->_memcached->set(sha1($key), array($data, time(), $ttl), 0, $ttl);
        }
    }

    public function get($key) {
        return isset($this->_lkeydata[sha1($key)]) ? $this->_lkeydata[sha1($key)] : null;
    }

    public function valid($key) {
        if ($_memcache_enabled) {
            $data = $this->_memcached->get(sha1($key));
            if ($data) {
                $this->_lkeydata[sha1($key)] = (is_array($data)) ? $data[0] : false;
                return true;
            }
        }
        return false;
    }

}
