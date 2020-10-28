<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DEBUG', '1');
    define('DB_CON', serialize(array('mysql', 'localhost', 'root', '', 'mypwo')));
} else {
    define('DEBUG', '0');
    define('DB_CON', serialize(array('mysql', 'localhost', '', '', '')));
}

$siteuri = ((mb_strstr('https', $_SERVER['SERVER_PROTOCOL']) === false) ? 'http' : 'https') . '://' . $_SERVER['SERVER_NAME'];
if ($_SERVER["SERVER_PORT"] != 80) {
    $siteuri .= ":" . $_SERVER["SERVER_PORT"];
}
define('SITE_URI', $siteuri);

define('PATH_URI', dirname($_SERVER["SCRIPT_NAME"]));

define('CONT_DIR', APP_DIR . 'controllers/');
define('LIBS_DIR', APP_DIR . 'libraries/');
define('VIEW_DIR', APP_DIR . 'views/');
define('MODS_DIR', APP_DIR . 'models/');

define('MAIN_CONTROLLER', 'home');
define('MAIN_METHOD', 'index');

define('SESS_TIMEOUT', 1800);
define('SESS_TYPE', 'Native');

ini_set('apc.cache_by_default', 0);

define('SYSTEM_TIMEZONE', 'Asia/Kathmandu');
