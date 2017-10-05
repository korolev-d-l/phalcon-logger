<?php

require_once BASE_PATH . '/vendor/autoload.php';

if (!APP_ENV_IS_TESTING) {
    $dotenv = new Dotenv\Dotenv(BASE_PATH, '.env');
    $dotenv->load();
    $dotenv->required('DB_ADAPTER')->allowedValues(['postgresql', 'sqlite', 'mysql']);
}
