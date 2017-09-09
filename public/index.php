<?php

use Phalcon\Mvc\Micro;

define('BASE_PATH', dirname(__DIR__));
define('APP_ENV', getenv('APP_ENV') ?: 'development');
ini_set('display_errors', boolval('development' === APP_ENV));
error_reporting(E_ALL);

try {

    include BASE_PATH . '/config/loader.php';
    include BASE_PATH . '/config/config.php';
    include BASE_PATH . '/config/services.php';

    $app = new Micro($di);
    include BASE_PATH . '/app.php';
    $app->handle();

} catch (\Exception $e) {
    echo $e->getMessage();
    if (APP_ENV === 'development') {
        echo '<pre>' . $e->getTraceAsString();
    }
}
