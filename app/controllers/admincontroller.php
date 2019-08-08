<?php

abstract class cAdminController {

    public $req = null;
    public $res = null;

    public function __construct() {
        $this->req = req();
        $this->res = res();

        $pathprefix = $this->req->getPathPrefix();

        $cusertype = getCurrentUserType();

        if ($pathprefix == 'dashboard' && $cusertype != 'user') {
            $this->res->redirect('manage/login', 'Invalid Access');
        }
        if ($pathprefix == 'admin' && $cusertype != 'superadmin') {
            $this->res->redirect('manage/login', 'Invalid Access');
        }
    }

}

