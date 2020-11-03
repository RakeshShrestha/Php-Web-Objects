<?php

abstract class cAdminController
{

    public $req = null;

    public $res = null;

    public function __construct()
    {
        $this->req = req();
        $this->res = res();

        $pathprefix = $this->req->getPathPrefix();

        $cusertype = getCurrentUserType();

        if ($pathprefix == 'dashboard' && $cusertype != 'user') {
            $this->res->redirect('login', 'Invalid Access');
        }
        if ($pathprefix == 'manage' && $cusertype != 'superadmin') {
            $this->res->redirect('login', 'Invalid Access');
        }
    }
}
