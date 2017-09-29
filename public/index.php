<?php

const APP_ENV_TYPE_PRODUCTION  = 'production';
const APP_ENV_TYPE_DEVELOPMENT = 'development';

define('BASE_PATH', dirname(__DIR__));
define('APP_ENV', getenv('APP_ENV') ?: APP_ENV_TYPE_DEVELOPMENT);
define('APP_ENV_IS_DEV', APP_ENV === APP_ENV_TYPE_DEVELOPMENT);
ini_set('display_errors', boolval(APP_ENV_IS_DEV));
error_reporting(E_ALL);

try {

    include BASE_PATH . '/config/loader.php';
    include BASE_PATH . '/config/config.php';
    include BASE_PATH . '/config/services.php';

    $app = new Phalcon\Mvc\Micro($di);
    include BASE_PATH . '/app.php';
    $app->handle();

} catch (\Exception $e) {
    echo $e->getMessage();
    if (APP_ENV_IS_DEV) {
        echo '<pre>' . $e->getTraceAsString();
    }
}
