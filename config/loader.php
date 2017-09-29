<?php

require_once BASE_PATH . '/vendor/autoload.php';

if (PHP_SAPI !== 'cli') {
    $dotenv = new Dotenv\Dotenv(BASE_PATH);
    $dotenv->load();
    $dotenv->required('DB_ADAPTER')->allowedValues(['postgresql', 'sqlite', 'mysql']);
}
