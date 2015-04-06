<?php

define('APP_PATH', realpath(dirname(__FILE__)) . '/');

define('CLASS_PATH', APP_PATH . 'webclasses/');

require_once(CLASS_PATH . 'config.php');

try {
    Dispatcher::process(Request::getContext());
} catch (ClassNotFoundException $e) {
    write_log('classnotfound', $e->getMessage() );
    exit();
} catch (LibraryNotFoundException $e) {
    write_log('librarynotfound', $e->getMessage() );
    exit();
} catch (ViewNotFoundException $e) {
    write_log('viewnotfound', $e->getMessage() );
    exit();
} catch (RequestException $e) {
    write_log('requestexception', $e->getMessage() );
    exit();
} catch (IOException $e) {
    write_log('ioexception', $e->getMessage() );
    exit();
} catch (DatabaseException $e) {
    write_log('databaseio', $e->getMessage() );
    exit();
} catch (Exception $e) {
    write_log('exception', $e->getMessage() );
    exit();
}
