<?php

$protocol = (strstr('https', $_SERVER['SERVER_PROTOCOL']) === false) ? 'http' : 'https';

define('SITE_URL', $protocol . '://' . $_SERVER['SERVER_NAME']);
define('SITE_PATH', dirname($_SERVER["SCRIPT_NAME"]));

define('PATH_PREFIX', serialize(array('rssview', 'jsonview')));

define('DEFAULT_TIMEZONE', 'Asia/Kathmandu');

define('MIX_GET_POST', true);

define('SESS_NAME', 'pwosession');
define('SESS_TIMEOUT', true);
define('SESS_TIMEOUT_VAL', 1800);

define('WRITE_LOG', false);
define('QUERY_LOG', false);
define('DEBUG', false);

define('DB_CON', serialize(array('mysql', 'localhost', 'root', '', 'test')));

require_once(CLASS_PATH . 'corefunctions.php');
require_once(CLASS_PATH . 'global.php');
