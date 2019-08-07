<?php

mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_regex_encoding('UTF-8');

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

if (!function_exists('url_encode')) {

    function url_encode($string = null) {
        return urlencode(utf8_encode($string));
    }

}

if (!function_exists('url_decode')) {

    function url_decode($string = null) {
        return utf8_decode(urldecode($string));
    }

}

if (!function_exists('array_map_recursive')) {

    function array_map_recursive($fn, &$arr) {
        foreach ($arr as $k => $v) {
            $arr[$k] = is_array($v) ? array_map_recursive($fn, $v) : $fn($v);
        }
        return $arr;
    }

}
