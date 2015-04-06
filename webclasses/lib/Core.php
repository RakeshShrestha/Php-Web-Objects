<?php

class Core {

    public $request = null;
    public $session = null;
    public $view = null;

    public function __construct() {
        $this->request = Request::getContext();
        $this->session = Session::getContext();
        $this->view = View::getContext();
    }

}
