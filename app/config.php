<?php

define('SITE_URI', ((mb_strstr('https', $_SERVER['SERVER_PROTOCOL']) === false) ? 'http' : 'https') . '://' . $_SERVER['SERVER_NAME']);
define('PATH_URI', dirname($_SERVER["SCRIPT_NAME"]));

define('MAIN_CONTROLLER', 'main');
define('MAIN_METHOD', 'index');

define('SITE_TITLE', 'My Website');

define('PATH_PREFIX', serialize(array('dashboard', 'admin', 'ajax')));

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    define('DB_CON', serialize(array('mysql', 'localhost', 'root', '', 'pwotest')));
} else {
    define('DB_CON', serialize(array('mysql', 'localhost', 'root', '', 'pwotest')));
}

define('SESS_TIMEOUT', 1800);
define('SESS_TYPE', 'Native');

define('SYSTEM_EMAIL', 'rakesh.shrestha@gmail.com');

define('DEBUG', '0');

define('PAGINATE_LIMIT', '5');

define('DEFAULT_TIMEZONE', 'America/New_York');

ini_set('apc.cache_by_default', 0);
