<?php

class View {

    private $_layout = 'layout';
    private $_title = 'PHP WEB OBJECTS';
    private $_properties = array();
    private static $_context = null;

    public static function getContext() {
        if (self::$_context === null) {
            self::$_context = new self;
        }
        return self::$_context;
    }

    public function __construct() {
        $req = Request::getContext();
        $this->mobileinuse = $req->isMobile();
        $this->layout = $this->_layout;

        $this->mainregion = strtolower($req->getController()) . '.' . $req->getMethod();
        $this->title = $this->_title;
        $this->lang = $req->getLang();
    }

    public function __set($key, $value) {
        $this->_properties[$key] = $value;
    }

    public function __get($key) {
        return $this->_properties[$key];
    }

    public function display($withlayout = true, $expiryval = 0) {
        $this->session = Session::getContext();
        if (!$withlayout) {
            $this->layout = $this->mainregion;
        } else {
            if ($this->mobileinuse) {
                $this->layout = 'mobile_' . $this->layout;
            }
        }
        extract($this->_properties);
        ob_start();
        include (prepare_view($layout));
        echo ob_get_clean();
    }

}
