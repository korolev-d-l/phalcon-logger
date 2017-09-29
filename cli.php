<?php

const APP_ENV_TYPE_PRODUCTION  = 'production';
const APP_ENV_TYPE_DEVELOPMENT = 'development';

define('BASE_PATH', __DIR__);
define('APP_ENV', getenv('APP_ENV') ?: APP_ENV_TYPE_DEVELOPMENT);
define('APP_ENV_IS_DEV', APP_ENV === APP_ENV_TYPE_DEVELOPMENT);
ini_set('display_errors', 1);
error_reporting(E_ALL);

include BASE_PATH . '/config/loader.php';
include BASE_PATH . '/config/config.php';
include BASE_PATH . '/config/services.php';

$console = new Phalcon\Cli\Console();
$console->setDI($di);
$di->setShared('console', $console);
$di->setShared('config', $config);

$arguments = [];

foreach ($argv as $k => $arg) {
    if ($k === 1) {
        $arguments['task'] = $arg;
    } elseif ($k === 2) {
        $arguments['action'] = $arg;
    } elseif ($k >= 3) {
        $arguments['params'][] = $arg;
    }
}

try {
    $console->handle($arguments);
} catch (\Throwable $t) {
    $throwableInfo = sprintf(
        'CODE[%d]: %s' . PHP_EOL . '%s:%s' . PHP_EOL . '%s' . PHP_EOL,
        $t->getCode(),
        $t->getMessage(),
        $t->getFile(),
        $t->getLine(),
        $t->getTraceAsString()
    );
    fwrite(STDERR, $throwableInfo);
    exit(1);
}