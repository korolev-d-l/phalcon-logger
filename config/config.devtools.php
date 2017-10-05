<?php

const APP_ENV_TYPE_PRODUCTION  = 'production';
const APP_ENV_TYPE_DEVELOPMENT = 'development';
const APP_ENV_TYPE_TESTING     = 'testing';

define('BASE_PATH', dirname(__DIR__));
define('APP_ENV', getenv('APP_ENV') ?: APP_ENV_TYPE_DEVELOPMENT);
define('APP_ENV_IS_PRODUCTION', APP_ENV === APP_ENV_TYPE_PRODUCTION);
define('APP_ENV_IS_DEV', APP_ENV === APP_ENV_TYPE_DEVELOPMENT);
define('APP_ENV_IS_TESTING', APP_ENV === APP_ENV_TYPE_TESTING);
define('APP_START_TIME', microtime(true));
define('APP_START_MEMORY', memory_get_usage());

include BASE_PATH . '/config/loader.php';
include BASE_PATH . '/config/config.php';

return $config;