<?php

class Controller extends Core {

    public function __construct() {
        parent::__construct();
        $this->request->setLang('en');

        echo $this->request->getPathPrefix();
        echo '<br />';
        echo $this->request->getController();
        echo '<br />';
        echo $this->request->getMethod();
        echo '<br />';
        print_r($this->request->getArgs());
        echo '<br />';
    }

}
