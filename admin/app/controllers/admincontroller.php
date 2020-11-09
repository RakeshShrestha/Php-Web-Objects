<?php
# Copyright Rakesh Shrestha (rakesh.shrestha@gmail.com)
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
#
# Redistributions must retain the above copyright notice.

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
