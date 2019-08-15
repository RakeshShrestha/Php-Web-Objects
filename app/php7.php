<?php

if (!function_exists('str_rot13')) {

    function str_rot13($str) {
        $from = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $to = 'nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM';

        return strtr($str, $from, $to);
    }

}

if (!function_exists('str_rotr13')) {

    function str_rotr13($str) {
        $from = 'nopqrstuvwxyzabcdefghijklmNOPQRSTUVWXYZABCDEFGHIJKLM';
        $to = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return strtr($str, $from, $to);
    }

}

if (!function_exists('mb_ucwords')) {

    function mb_ucwords($str = null) {
        return mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
    }

}

if (!function_exists('mb_str_replace')) {

    function mb_str_replace($needle, $replacement, $haystack) {
        return str_replace($needle, $replacement, $haystack);
    }

}

if (!function_exists('mb_trim')) {

    function mb_trim($string) {
        $string = preg_replace("/(^\s+)|(\s+$)/us", "", $string);

        return $string;
    }

}

if (!function_exists('array_walk_recursive')) {

    function array_walk_recursive(&$arr, $fn) {
        foreach ($arr as $k => $v) {
            $arr[$k] = is_array($v) ? array_walk_recursive($v, $fn) : $fn($v);
        }
        return $arr;
    }

}
