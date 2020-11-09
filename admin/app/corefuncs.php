<?php
# Copyright Rakesh Shrestha (rakesh.shrestha@gmail.com)
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
#
# Redistributions must retain the above copyright notice.

function getUrl($path = null)
{
    if (PATH_URI != '/') {
        return SITE_URI . PATH_URI . '/' . $path;
    } else {
        return SITE_URI . '/' . $path;
    }
}

function clean($string = null)
{
    return strip_tags(mb_trim($string));
}

function cleanHtml($html = null)
{
    $allowed_tags = array();
    $rhtml = preg_replace_callback('/<\/?([^>\s]+)[^>]*>/i', function ($matches) use (&$allowed_tags) {
        return in_array(mb_strtolower($matches[1]), $allowed_tags) ? $matches[0] : '';
    }, $html);
    return $rhtml;
}

function req()
{
    return Request::getContext();
}

function res()
{
    return Response::getContext();
}

function getRequestIP()
{
    return $_SERVER['REMOTE_ADDR'];
}

function genUID()
{
    return mb_substr(md5(uniqid(rand())), 0, 12);
}

function genGUID()
{
    $data = openssl_random_pseudo_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function createDir($path, $mode = 0777, $rec = true)
{
    $oldumask = umask(0);
    mkdir($path, $mode, $rec);
    umask($oldumask);
}

function writeLog($type = 'mylog', $msg = null)
{
    $file = APP_DIR . 'logs/' . $type . '.txt';
    $datetime = @date('Y-m-d H:i:s');
    $logmsg = '###' . $datetime . '### ' . $msg . "\r\n";
    @file_put_contents($file, $logmsg, FILE_APPEND | LOCK_EX);
}

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_regex_encoding('UTF-8');

function mb_ucwords($str = null)
{
    return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
}

function mb_str_replace($needle, $replacement, $haystack)
{
    return str_replace($needle, $replacement, $haystack);
}

function mb_trim($string)
{
    $string = preg_replace("/(^\s+)|(\s+$)/us", "", $string);

    return $string;
}

function url_encode($string = null)
{
    return urlencode(utf8_encode($string));
}

function url_decode($string = null)
{
    return utf8_decode(urldecode($string));
}

function array_map_recursive($fn, $arr)
{
    $rarr = array();
    foreach ($arr as $k => $v) {
        $rarr[$k] = is_array($v) ? array_map_recursive($fn, $v) : $fn($v);
    }
    return $rarr;
}

if (DEBUG) {

    function customError($errno, $errstr, $errfile, $errline)
    {
        echo "<div class='error' style='text-align:left'>";
        echo "<b>Custom error:</b> [$errno] $errstr<br />";
        echo "Error on line $errline in $errfile<br />";
        echo "Ending Script";
        echo "</div>";
    }

    set_error_handler("customError");
}
