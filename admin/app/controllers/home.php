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

final class cHome extends cController
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
        if (getCurrentUserType() != 'superadmin') {
            $this->res->redirect('login', 'Invalid Access');
        }
        $data['pagetitle'] = SITE_TITLE;
        $this->res->display($data);
    }

    public function dashboard_index()
    {
        if (getCurrentUserType() != 'admin') {
            $this->res->redirect('login', 'Invalid Access');
        }
        $data['pagetitle'] = SITE_TITLE;
        $this->res->display($data);
    }

    public function download($id = 1)
    {
        $mdoc = new model('cms_docs');
        $mdoc->select('*', 'id=?', $id);

        $filename = mb_str_replace(" ", "-", $mdoc->docsname);
        $readfilename = $mdoc->docslink;

        $size = filesize('uploads/documents/' . $readfilename);

        header('Content-Description: File Transfer');
        header('Content-Transfer-Encoding: binary');
        header("Content-type: " . my_mime_content_type($filename));
        header("Content-Disposition: attachment; filename=" . url_encode($filename) . "");
        header('Connection: Keep-Alive');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . $size);
        readfile("uploads/documents/" . $readfilename);
    }

    public function feedback()
    {
        if ($this->req->isPost()) {
            $mail = new Email();
            $mail->setFrom(SYSTEM_EMAIL, 'Feedback Form');
            $mail->setTO(SYSTEM_EMAIL, 'Admin');
            $mail->setCC(SYSTEM_EMAIL2, 'MYAdmin');
            $mail->setSubject('Feedback');

            /* XSS Prevent */
            $_POST = array_map_recursive("cleanHtml", $_POST);

            $message = "Got feedback on dated " . date('Y-m-d H:i:s') . " from ip " . getRequestIP() . "\r\n\r\n";
            $message .= "Details:\r\n Name: " . $_POST['contact_name'] . "\r\n Email: " . $_POST['contact_email'] . "\r\n ";
            $message .= "Phone: " . $_POST['contact_phone'] . "\r\n Email: \r\n\r\n" . $_POST['contact_message'] . "\r\n ";

            $mail->setMessage($message);

            $mail->send();

            echo 'Feedback sent successfully';
        }
    }
}
