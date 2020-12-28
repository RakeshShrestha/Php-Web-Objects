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
            throw new ApiException('Invalid Access.');
        }

        // split the token
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // build a signature based on the header and payload using the secret
        $base64UrlHeader = base64_url_encode($header);
        $base64UrlPayload = base64_url_encode($payload);
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, SECRET_KEY, true);
        $base64UrlSignature = base64_url_encode($signature);

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the token
        $expiration = json_decode($payload)->exp;
        $tokenExpired = (time() > $expiration);

        if ($tokenExpired) {
            throw new ApiException('Token has expired.');
        }

        // verify it matches the signature provided in the token
        $signatureValid = ($base64UrlSignature === $signatureProvided);

        if ($signatureValid) {
            $this->user = json_decode($payload);
        } else {
            throw new ApiException('Signature is not vald.');
        }
    }
}
