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
final class cUser extends cController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function api_login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        /* XSS Prevention */
        $data = array_map_recursive($data, "cleanHtml");

        if ($this->req->isPost()) {

            $user = new model('v_user_master');

            $username = $data['username'];
            $password = $data['password'];

            $user->select('*', 'email=? and password=?', array(
                $username,
                $password
            ));

            if ($user->exist()) {

                $header = json_encode([
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ]);

                // Create the token payload
                $payload = json_encode([
                    'userid' => $user->id,
                    'roles' => '["admin-token"]',
                    'introduction' => $user->c_name,
                    'name' => $user->email,
                    'avatar' => '',
                    'usertype' => $user->n_user_type,
                    'reportunit' => $user->n_reportingunit_id,
                    'exp' => time() + 24 * 3600
                ]);

                // Encode Header
                $base64UrlHeader = base64UrlEncode($header);

                // Encode Payload
                $base64UrlPayload = base64UrlEncode($payload);

                // Create Signature Hash
                $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET_KEY, true);

                // Encode Signature to Base64Url String
                $base64UrlSignature = base64UrlEncode($signature);

                // Create JWT
                $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

                echo json_encode(array(
                    'code' => 20000,
                    'data' => array(
                        'token' => $jwt
                    )
                ));
            } else {
                echo json_encode(array(
                    'success' => false,
                    'msg' => 'Error Login'
                ));
            }
        }
    }

    public function api_logout()
    {
        echo json_encode(array(
            'code' => 20000,
            'data' => "success"
        ));
    }
}
