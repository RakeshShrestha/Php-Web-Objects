<?php

function site_url($path = '') {
    return SITE_URL . '' . SITE_PATH . '' . $path;
}

function redirect($path = '') {
    $redir = SITE_URL . '' . SITE_PATH . '' . $path;
    header("Location: $redir");
}

function query_log($msg = '') {
    if (QUERY_LOG) {
        $file = CLASS_PATH . 'cachetmp/logs/queries.txt';
        $datetime = @date('Y-m-d H:i:s');
        $logmsg = '###' . $datetime . '### ' . $msg . "\r\n";
        @file_put_contents($file, $logmsg, FILE_APPEND | LOCK_EX);
    }
}

function write_log($type = 'exception', $msg = '') {
    if (WRITE_LOG) {
        $file = CLASS_PATH . 'cachetmp/logs/' . $type . '.txt';
        $datetime = @date('Y-m-d H:i:s');
        $logmsg = '###' . $datetime . '### ' . $msg . "\r\n";
        @file_put_contents($file, $logmsg, FILE_APPEND | LOCK_EX);
    }
}

function str_sanitize($string) {
    $search = array('/[^\w\-\. ]+/u', '/\s\s+/', '/\.\.+/', '/--+/', '/__+/');
    return trim(preg_replace($search, array(' ', ' ', '.', '-', '_'), $string), '-._ ');
}

function real_strip_tags($html) {
    $allowedtags = array('div', 'a', 'p', 'strong', 'br', 'ul', 'li', 'img', 'object', 'embed', 'param');
    $disabledattributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
    return preg_replace('/<(.*?)>/ie', "'<' . preg_replace(array('/javascript:[^\"\']*/i', '/(" . implode('|', $disabledattributes) . ")[ \\t\\n]*=[ \\t\\n]*[\"\'][^\"\']*[\"\']/i', '/\s+/'), array('', '', ' '), stripslashes('\\1')) . '>'", strip_tags($html, implode('', $allowedtags)));
}

function do_secure_uri($str) {
    if (!preg_match("|^[" . str_replace(array('\\-', '\-'), '-', preg_quote('a-z0-9\-', '-')) . "]+$|i", (string) $str)) {
        throw new RequestException('Invalid request found');
    }
}

function mk_dir($path, $mode = 0777, $rec = true) {
    $oldumask = umask(0);
    mkdir($path, $mode, $rec);
    umask($oldumask);
}

function import($library) {
    require_once CLASS_PATH . 'lib/' . str_replace('.', '/', $library) . '.php';
}

function prepare_view($viewname) {
    if (is_readable(CLASS_PATH . 'views/' . str_replace('.', '/', $viewname) . '.php')) {
        return (CLASS_PATH . 'views/' . str_replace('.', '/', $viewname) . '.php');
    } else {
        throw new ViewNotFoundException('view: 404 - ' . $viewname . ' not found');
    }
}

