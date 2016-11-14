<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DEBUG', '1');
    define('DB_CON', serialize(array('mysql', 'localhost', 'root', '', 'pwotest')));
} else {
    define('DEBUG', '0');
    define('DB_CON', serialize(array('mysql', 'localhost', 'root', '', 'pwotest')));
}

define('SITE_URI', ((mb_strstr('https', $_SERVER['SERVER_PROTOCOL']) === false) ? 'http' : 'https') . '://' . $_SERVER['SERVER_NAME']);
define('PATH_URI', dirname($_SERVER["SCRIPT_NAME"]));

define('CONT_DIR', APP_DIR . 'controllers/');
define('LIBS_DIR', APP_DIR . 'libraries/');
define('VIEW_DIR', APP_DIR . 'views/');
define('MODS_DIR', APP_DIR . 'models/');

define('MAIN_CONTROLLER', 'main');
define('MAIN_METHOD', 'index');

define('SESS_TIMEOUT', 1800);
define('SESS_TYPE', 'Database');

ini_set('apc.cache_by_default', 0);

define('SYSTEM_TIMEZONE', 'UTC');
