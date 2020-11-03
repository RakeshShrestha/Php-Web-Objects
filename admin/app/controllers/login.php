<?php

final class cLogin extends cController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = array();

        $data['username'] = '';
        $data['user'] = '';

        if (getCurrentUserType() == 'superadmin') {
            $this->res->redirect('manage');
        }
        if (getCurrentUserType() == 'user') {
            $this->res->redirect('dashboard');
        }

        $user = new user();

        if ($this->req->isPost()) {
            $username = $_POST['username'];
            $password = md5($_POST['password']);

            $user->select('*', 'username=?', $username);

            if (! $user->exist()) {
                $this->res->redirect('login/', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">LOGIN FAILED!</div>');
            }
            if ($password != $user->password) {
                $this->res->redirect('login/', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">WRONG PASSWORD!</div>');
            }
            if ($user->status == 2) {
                $this->res->redirect('login/', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">LOGIN FAILED!</div>');
            }

            setCurrentUser($user->get());

            if (getCurrentUserType() == 'superadmin') {
                $this->res->redirect('manage');
            }
            if (getCurrentUserType() == 'user') {
                $this->res->redirect('dashboard');
            }

            $data['username'] = $username;
            $data['user'] = $user;

            exit();
        }

        $this->res->display($data, 'main/login_form');
    }

    public function forgotpass()
    {
        $data = array();

        $data['username'] = '';
        $data['user'] = '';

        if (getCurrentUserType() == 'superadmin') {
            $this->res->redirect('manage');
        }
        if (getCurrentUserType() == 'user') {
            $this->res->redirect('dashboard');
        }

        $user = new user();

        if ($this->req->isPost()) {
            $username = $_POST['username'];

            $user->select('*', 'username=?', $username);

            if (! $user->exist()) {
                $this->res->redirect('login/forgotpass', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">User does not exit!</div>');
            }
            if ($user->status == 2) {
                $this->res->redirect('login/forgotpass', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">User does not exit!</div>');
            }

            $mail = new Email();
            $mail->setFrom(SYSTEM_EMAIL, 'RUDP System');
            $mail->setTO($user->username, $user->firstname);
            $mail->setSubject('Forgot Password');

            $message = "Dear " . $user->firstname . ",\r\n\r\nYour Forgot Password.\r\n\r\n";
            $message .= "Details:\r\nEmail:  $user->username \r\nPassword: $user->remarks \r\n ";
            $message .= "\r\nLogin Url: " . getUrl('manage/login');

            $mail->setMessage($message);

            // print_r($message);
            $mail->send();
            $this->res->redirect('login/forgotpass', '<div style="font-size:13px; color:#ff0000; margin-bottom:4px; margin-top:8px;">Your password has been mailed to you!</div>');
        }

        $this->res->display($data, 'main/login_forgotpass');
    }
}
