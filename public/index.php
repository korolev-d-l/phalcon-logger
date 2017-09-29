<?php

use Phalcon\Mvc\Micro;

define('BASE_PATH', dirname(__DIR__));
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_ENV_IS_DEV', APP_ENV === 'development');
ini_set('display_errors', boolval(APP_ENV_IS_DEV));
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
    if (APP_ENV_IS_DEV) {
        echo '<pre>' . $e->getTraceAsString();
    }
}
