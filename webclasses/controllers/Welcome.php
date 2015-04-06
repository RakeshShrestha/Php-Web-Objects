<?php

class Welcome extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $view = $this->view;

        $view->title = 'Home';
        $view->layout = 'custom_layout';
        $view->mainregion = 'msg.show';

        $view->display();
    }

    public function rssview_test() {
        $view = $this->view;

        $view->title = 'This is rssview pathprefix test';

        $view->display();
    }

    public function test() {
        echo 'This is text without any layout and template';
    }

}
