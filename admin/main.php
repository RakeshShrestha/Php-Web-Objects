<?php
# Copyright Rakesh Shrestha (rakesh.shrestha@gmail.com)
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without
# modification, are permitted provided that the following conditions are
# met:
#
# Redistributions must retain the above copyright notice.

define('APP_DIR', realpath(dirname(__FILE__)) . '/app/');

require_once APP_DIR . 'config/config.php';

if (DEBUG) {
    error_reporting(E_ALL);
} else {
    error_reporting(0);
}

require_once APP_DIR . 'coreclasses.php';

date_default_timezone_set(SYSTEM_TIMEZONE);

$request = Request::getContext();
$response = Response::getContext();

try {
    Application::run($request, $response);
} catch (Exception $e) {
    $data['message'] = DEBUG ? $e : '';

    if ($request->isAjax()) {
        $data['layout'] = false;
    }

    $response->display($data, 'errors/exception');
    exit;
}
