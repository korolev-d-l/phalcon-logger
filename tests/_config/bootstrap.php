<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(dirname(__DIR__)));
    define('APP_ENV_TYPE_PRODUCTION', 'production');
    define('APP_ENV_TYPE_DEVELOPMENT', 'development');
    define('APP_ENV_TYPE_TESTING', 'testing');
    define('APP_ENV', getenv('APP_ENV') ?: APP_ENV_TYPE_DEVELOPMENT);
    define('APP_ENV_IS_PRODUCTION', APP_ENV === APP_ENV_TYPE_PRODUCTION);
    define('APP_ENV_IS_DEV', APP_ENV === APP_ENV_TYPE_DEVELOPMENT);
    define('APP_ENV_IS_TESTING', APP_ENV === APP_ENV_TYPE_TESTING);
    define('APP_START_TIME', microtime(true));
    define('APP_START_MEMORY', memory_get_usage());
}

error_reporting(APP_ENV_IS_TESTING ? -1 : E_ALL);
ini_set('display_errors', boolval(APP_ENV_IS_DEV || APP_ENV_IS_TESTING));
ini_set('display_startup_errors', boolval(APP_ENV_IS_TESTING));

if (PHP_SAPI == 'cli' && extension_loaded('xdebug')) {
    ini_set('xdebug.cli_color', 1);
    ini_set('xdebug.collect_params', 0);
    ini_set('xdebug.dump_globals', 'on');
    ini_set('xdebug.show_local_vars', 'on');
    ini_set('xdebug.max_nesting_level', 100);
    ini_set('xdebug.var_display_max_depth', 4);
}

include BASE_PATH . '/config/loader.php';
include BASE_PATH . '/config/config.php';
include BASE_PATH . '/config/services.php';

$app = new Phalcon\Mvc\Micro($di);
include BASE_PATH . '/app.php';
return $app;