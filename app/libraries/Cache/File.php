<?php

final class Cache_File {

    private $_cachedir = '';
    private $_lkeydata = array();

    public function __construct() {
        $cachedir = APP_DIR . 'logs/';
        if (!is_dir($cachedir) or ! is_writable($cachedir)) {
            throw new Exception('Cache directory must be a valid writeable directory.');
            return;
        }
        $this->_cachedir = $cachedir;
    }

    public function set($key, $data, $ttl = 180) {
        $data = serialize(array(time() + $ttl, $data));
        $file = $this->_cachedir . sha1($key);
        if (file_exists($file)) {
            unlink($file);
        }

        if (!@file_put_contents($file, $data)) {
            throw new Exception('Error writing data to cache file.');
        }
    }

    public function get($key) {
        return isset($this->_lkeydata[sha1($key)]) ? $this->_lkeydata[sha1($key)] : null;
    }

    public function valid($key) {
        $file1 = glob($this->_cachedir . sha1($key));
        array_shift($file1);
        $filename = $this->_cachedir . sha1($key);
        if (file_exists($filename)) {
            $data1 = @file_get_contents($filename);
            $data = unserialize($data1);

            if (time() > $data[0]) {
                unlink($filename);
                return false;
            } else {
                $this->_lkeydata[sha1($key)] = $data[1];
                return true;
            }
        }
        return false;
    }

}
