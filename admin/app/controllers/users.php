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

final class cUsers extends cAdminController
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

    public function manage_index()
    {
        $data['pagename'] = 'Users';

        $db = DB::getContext();
        
        $user = new user();
		$data['users'] = $user->select('*');

        $this->res->display($data);
    }

    public function manage_add()
    {
        $data['pagename'] = 'Users';

        $user = new user();

        if ($this->req->isPost()) {
            $vars = $_POST;
			
			$vars['password'] = md5($vars['password']);
			$vars['remarks'] = $vars['password'];
			
            unset($vars['confirm_password']);
            unset($vars['iserror1']);
            unset($vars['iserror2']);
            unset($vars['submit']);

            $user->assign($vars);
            $user->insert();

            $this->res->redirect('admin/users', 'User Added Successfully');
        }

        $this->res->display($data);
    }

    public function manage_edit($id = 0)
    {
        $data['pagename'] = 'Users';

        $user = new user($id);

        $data['user'] = $user;

        if ($this->req->isPost()) {
            $vars = $_POST;
            if ($vars['password']) {
                $vars['password'] = md5($vars['password']);
                $vars['remarks'] = $vars['password'];
            } else {
                $vars['password'] = $user->password;
            }
            unset($vars['confirm_password']);
            unset($vars['iserror1']);
            unset($vars['iserror2']);
            unset($vars['submit']);

            $user->assign($vars);
            $user->update();

            $this->res->redirect('admin/users', 'User Updated Successfully');
        }

        $this->res->display($data);
    }

    public function manage_disable($userid = 0)
    {
        $data['pagename'] = 'Users';

        $user = new user($userid);
		$user->status = 2;
		$user->update();

        $this->res->redirect('admin/users', 'User Disabled');
    }

    public function manage_enable($userid = 0)
    {
        $data['pagename'] = 'Users';

        $user = new user($userid);
		$user->status = 1;
		$user->update();

        $this->res->redirect('admin/users', 'User Enabled');
    }
}
