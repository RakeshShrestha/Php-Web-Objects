<?php

final class Cache_Memcache {

    private $_memcache = null;
    private $_memcache_enabled = null;
    private $_lkeydata = array();

    public function __construct() {
        $this->_memcache_enabled = extension_loaded('memcache');
        if ($this->_memcache_enabled) {
            $this->_memcache = new Memcache();
            $this->_memcache->addserver(key(unserialize(MC_SERVER)), current(unserialize(MC_SERVER)));
        }
    }

    public function set($key, $data, $ttl = 3600) {
        if ($this->_memcache_enabled) {
            return $this->_memcache->set(sha1($key), array($data, time(), $ttl), 0, $ttl);
        }
    }

    public function get($key) {
        return isset($this->_lkeydata[sha1($key)]) ? $this->_lkeydata[sha1($key)] : null;
    }

    public function valid($key) {
        if ($this->_memcache_enabled) {
            $data = $this->_memcache->get(sha1($key));
            if ($data) {
                $this->_lkeydata[sha1($key)] = (is_array($data)) ? $data[0] : false;
                return true;
            }
        }
        return false;
    }

}
