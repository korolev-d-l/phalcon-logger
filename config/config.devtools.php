<?php

define('BASE_PATH', dirname(__DIR__));
define('APP_ENV', getenv('APP_ENV') ?: 'development');

include BASE_PATH . '/config/loader.php';
include BASE_PATH . '/config/config.php';

return $config;