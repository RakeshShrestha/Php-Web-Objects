<?php

final class cUsers extends cAdminController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data['pagetitle'] = SITE_TITLE;
        $this->res->display($data);
    }

    public function manage_index() {
        $data['pagename'] = 'Users';

        $db = DB::getContext();

        $stmt = $db->prepare("SELECT * FROM users WHERE perms='user' ORDER BY created DESC");
        $stmt->execute();

        $data['users'] = $stmt->fetchAll();

        $this->res->display($data);
    }

    public function manage_add($id = 0) {
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
            $user->insert();

            $this->res->redirect('admin/users', 'User Updated Successfully');
        }

        $this->res->display($data);
    }

    public function manage_edit($id = 0) {
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

    public function manage_disable($userid = 0) {
        $data['pagename'] = 'Users';

        $db = DB::getContext();
        $stmt = $db->prepare("UPDATE users SET status='2' WHERE id=? ");
        $stmt->bindValue(1, $userid);
        $stmt->execute();

        $this->res->redirect('admin/users', 'User Disabled');
    }

    public function manage_enable($userid = 0) {
        $data['pagename'] = 'Users';

        $db = DB::getContext();
        $stmt = $db->prepare("UPDATE users SET status='1' WHERE id=? ");
        $stmt->bindValue(1, $userid);
        $stmt->execute();

        $this->res->redirect('admin/users', 'User Enabled');
    }

}
