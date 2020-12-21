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
abstract class cAuthController
{

    public $req = null;

    public $res = null;

    public $user = null;

    public function __construct()
    {
        $this->req = req();
        $this->res = res();

        $jwt = $this->req->getToken();

        if (! $jwt) {
            echo json_encode(array(
                'success' => false,
                'msg' => 'Ivalid Access.'
            ));
            exit();
        }

        // split the token
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = base64UrlEncode($header);
        $base64UrlPayload = base64UrlEncode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET_KEY, true);
        $base64UrlSignature = base64UrlEncode($signature);

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
        $expiration = json_decode($payload)->exp;
        $tokenExpired = (time() > $expiration);

        if ($tokenExpired) {
            echo json_encode(array(
                'success' => false,
                'msg' => 'Token has expired.'
            ));
            exit();
        }

        // verify it matches the signature provided in the token
        $signatureValid = ($base64UrlSignature === $signatureProvided);

        if ($signatureValid) {
            $this->user = json_decode($payload);
        } else {
            echo json_encode(array(
                'success' => false,
                'msg' => 'Signatur is not vald'
            ));
            exit();
        }
    }
}
