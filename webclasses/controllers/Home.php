<?php

class Home extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $sess = $this->session;
        $sess->test = array('1', '2', '3');

        $view = $this->view;

        $view->title = 'Home';

        $view->display();
    }

}
