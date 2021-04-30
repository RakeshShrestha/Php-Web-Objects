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
final class Encryption
{

    private $_skey = "ThisIsCOOL";

    public function encode($value)
    {
        if (! $value) {
            return false;
        }
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->_skey, $text, MCRYPT_MODE_ECB, $iv);
        return mb_trim(base64_url_encode($crypttext));
    }

    public function decode($value)
    {
        if (! $value) {
            return false;
        }
        $crypttext = base64_url_decode($value);
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->_skey, $crypttext, MCRYPT_MODE_ECB, $iv);
        return mb_trim($decrypttext);
    }
}
