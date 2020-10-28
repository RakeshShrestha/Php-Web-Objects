<?php

abstract class cController {

    public $req = null;
    public $res = null;

    public function __construct() {
        $this->req = req();
        $this->res = res();
    }

}
