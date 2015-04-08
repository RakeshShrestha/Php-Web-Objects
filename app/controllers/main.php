<?php

class cMain extends cController {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->redirect('main/login');
    }

    public function dashboard_index() {
        $user_id = getCurrentUserID();

        $this->display();
    }

    public function admin_index() {
        $this->display();
    }

    public function login($username = '') {
        if (getCurrentUser()) {
            if (getCurrentUserType() == 'superadmin') {
                $this->redirect('admin');
            } else {
                $this->redirect('dashboard');
            }
        }

        $user = new user();

        if ($this->req->isPost()) {
            $username = clean($_POST['username']);
            $password = md5($_POST['password']);

            $user->select('*', 'username=?', $username);

            if (!$user->exist()) {
                $this->redirect('main/login/' . $username, '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">LOGIN FAILED!</div>');
            }
            if ($password != $user->password) {
                $this->redirect('main/login/' . $username, '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">WRONG PASSWORD!</div>');
            }
            if (!$user->status == 3) {
                $this->redirect('main/login/' . $username, '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">LOGIN FAILED!</div>');
            }

            setCurrentUser($user->get());

            if (getCurrentUserType() == 'superadmin') {
                $this->redirect('admin');
            } else {
                $this->redirect('dashboard');
            }
        }
        $data['username'] = $username;
        $data['user'] = $user;

        $this->display($data, 'main/login_form');
    }

    public function register($username = '') {
        if (getCurrentUser()) {
            if (getCurrentUserType() == 'superadmin') {
                $this->redirect('admin');
            } else {
                $this->redirect('dashboard');
            }
        }

        $user = new user();

        $data['username'] = $username;
        $data['registerusername'] = $username;

        if ($this->req->isPost()) {
            $db = DB::getContext();

            $user->assign($_POST['data']);
            $user_email = $_POST['data']['username'];
            $userpassword = $_POST['data']['password'];

            $user->insert();

            $mail = new Email();
            $mail->setFrom(SYSTEM_EMAIL, 'Registration');
            $mail->setTO($user_email, $_POST['data']['firstname']);
            $mail->setSubject('Registration');

            $message = "Dear " . $_POST['data']['firstname'] . " " . $_POST['data']['lastname'] . "\r\n\r\nYour Registration is complete.\r\n\r\n";
            $message .= "Details:\r\nusername:  $user_email \r\npassword: $userpassword \r\n ";
            $message .= "\r\nLogin Url: " . getUrl('main/login');

            $mail->setMessage($message);

            $send = $mail->send();
            $this->redirect('main/register/' . $user_email);
        }

        $this->display($data, 'main/login_form');
    }

    public function logout() {
        setCurrentUser();

        $this->redirect('main', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">You have logged out!</div>');
    }

    public function ajax_userexist() {
        $db = DB::getContext();

        $stmt = $db->prepare("SELECT username FROM users WHERE username=?");
        $stmt->bindValue(1, $_POST['username']);

        $stmt->execute();

        echo $stmt->rowCount();
    }

    public function ajax_userexist_account() {
        $db = DB::getContext();

        $stmt = $db->prepare("SELECT username FROM users WHERE username = ? and id <> ?");
        $stmt->bindValue(1, $_POST['username']);
        $stmt->bindValue(2, $_POST['id']);

        $stmt->execute();

        echo $stmt->rowCount();
    }

    public function dashboard_myprofile() {
        $data['pagename'] = 'Welcome';

        $user = new user(getCurrentUserID());

        $opassword = $user->password;
        $oemail = $user->username;

        if ($this->req->isPost()) {
            $vars = $_POST;
            if ($vars['password']) {
                $inputpassword = $vars['password'];
                $vars['password'] = md5($vars['password']);
            } else {
                $vars['password'] = $user->password;
            }

            $msend = 0;
            if (md5($vars['prepassword']) == $user->password) {
                $user->assign($vars);
                $user->update();

                if ($opassword != $user->password) {
                    $mail = new Email();
                    $mail->setFrom(SYSTEM_EMAIL, 'Account Password Change');
                    $mail->setTO($oemail, $user->firstname);
                    $mail->setSubject('Account Password Change');

                    $message = "Dear " . $user->firstname . " " . $user->lastname . "\r\n\r\nYou have successfully changed your account password.\r\n\r\n";
                    $message .= "Details:\r\nusername:  $user->username \r\npassword: " . $inputpassword . " \r\n ";
                    $message .= "\r\nLogin Url: " . getUrl('main/login');

                    $mail->setMessage($message);

                    $send = $mail->send();
                    $msend = 1;
                }

                if (!$msend && $oemail != $user->username) {
                    $mail = new Email();
                    $mail->setFrom(SYSTEM_EMAIL, 'Account Password Change');
                    $mail->setTO($oemail, $user->firstname);
                    $mail->setSubject('Account Email Change');

                    $message = "Dear " . $user->firstname . " " . $user->lastname . "\r\n\r\nYou have successfully changed your account email.\r\n\r\n";
                    $message .= "Details:\r\nusername:  $user->username \r\npassword: " . $vars['prepassword'] . " \r\n ";
                    $message .= "\r\nLogin Url: " . getUrl('main/login');

                    $mail->setMessage($message);

                    $send = $mail->send();
                }

                $this->redirect('dashboard/main/myprofile', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">Profile Updated Successfully</div>');
            } else {
                $data['prepassworderror'] = 'Please Enter Correct Password';
            }
        }

        $data['authuser'] = $user;

        $this->display($data);
    }

    public function admin_myprofile() {
        $data['pagename'] = 'Welcome';

        $user = new user(getCurrentUserID());

        if ($this->req->isPost()) {
            $vars = $_POST;
            if ($vars['password']) {
                $vars['password'] = md5($vars['password']);
            } else {
                $vars['password'] = $user->password;
            }

            $user->assign($vars);
            $user->update();

            $this->redirect('admin/main/myprofile', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">Profile Updated Successfully</div>');
        }

        $data['authuser'] = $user;

        $this->display($data);
    }

    public function admin_users() {
        $data['pagename'] = 'Welcome';

        $db = DB::getContext();

        $stmt = $db->prepare("SELECT * FROM users WHERE perms='user' ORDER BY created DESC");
        $stmt->execute();

        $data['users'] = $stmt->fetchAll();

        $this->display($data);
    }

    public function admin_disable($userid = 0) {
        $data['pagename'] = 'Welcome';

        $db = DB::getContext();
        $stmt = $db->prepare("UPDATE users SET status='3' WHERE id=? ");
        $stmt->bindValue(1, $userid);
        $stmt->execute();

        $this->redirect('admin/main/users', 'User Disabled');
    }

    public function admin_enable($userid = 0) {
        $data['pagename'] = 'Welcome';

        $db = DB::getContext();
        $stmt = $db->prepare("UPDATE users SET status='2' WHERE id=? ");
        $stmt->bindValue(1, $userid);
        $stmt->execute();

        $this->redirect('admin/main/users', 'User Enabled');
    }

    public function admin_contentlist() {
        $data['pagename'] = 'Manage Contents';

        $db = DB::getContext();

        $stmt = $db->prepare("SELECT * FROM pages ");
        $stmt->execute();

        $data['contents'] = $stmt->fetchAll();

        $this->display($data);
    }

    public function admin_contentedit($cid = 0) {
        $data['pagename'] = 'Manage Dashboard Content';

        $db = DB::getContext();

        if ($this->req->isPost()) {
            $stmt = $db->prepare("UPDATE pages SET pagecontent=? WHERE id=? ");
            $stmt->bindValue(1, trim($_POST['dashboard_content']));
            $stmt->bindValue(2, $cid);
            $stmt->execute();

            $this->redirect('admin/main/contentedit/' . $cid, '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">Content Updated Successfully</div>');
        }

        $stmt = $db->prepare("SELECT * FROM pages WHERE id=? ");
        $stmt->bindValue(1, $cid);
        $stmt->execute();

        $content = $stmt->fetch();

        if ($content) {
            $data['content'] = $content->pagecontent;
            $data['content_name'] = ucwords(str_replace(array('_', 'text'), ' ', $content->pagename));
            $data['content_id'] = $content->id;

            $this->display($data);
        } else {
            $this->redirect('admin/main/contentlist');
        }
    }

}
