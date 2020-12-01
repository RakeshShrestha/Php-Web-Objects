<?php

/**
 # Copyright Rakesh Shrestha (rakesh.shrestha@gmail.com)
 # All rights reserved.
 #
 # Redistribution and use in source and binary forms, with or without
 # modification, are permitted provided that the following conditions are
 # met:
 #
 # Redistributions must retain the above copyright notice.
 */
final class cMain extends cAdminController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['pagetitle'] = SITE_TITLE;
        $this->res->display($data);
    }

    public function manage_myprofile()
    {
        $data['pagename'] = 'Welcome';

        $user = new user(getCurrentUserID());

        if ($this->req->isPost()) {
            $vars = $_POST;
            if ($vars['password']) {
                $vars['password'] = md5($vars['password']);
                $vars['remarks'] = $vars['password'];
            } else {
                $vars['password'] = $user->password;
            }

            $user->assign($vars, true);
            $user->update();

            $this->res->redirect('manage/main/myprofile', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">Profile Updated Successfully</div>');
        }

        $data['authuser'] = $user;

        $this->res->display($data);
    }

    public function dashboard_myprofile()
    {
        $data['pagename'] = 'Welcome';

        $user = new user(getCurrentUserID());

        if ($this->req->isPost()) {
            $vars = $_POST;
            if ($vars['password']) {
                $vars['password'] = md5($vars['password']);
                $vars['remarks'] = $vars['password'];
            } else {
                $vars['password'] = $user->password;
            }

            $user->assign($vars, true);
            $user->update();

            $this->res->redirect('dashboard/main/myprofile', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">Profile Updated Successfully</div>');
        }

        $data['authuser'] = $user;

        $this->res->display($data);
    }

    public function logout()
    {
        setCurrentUser();

        $this->res->redirect('login', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">You have logged out!</div>');
    }
}
