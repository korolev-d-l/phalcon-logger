<?php
/** @var \Phalcon\Config $config */

if (PHP_SAPI === 'cli' && !APP_ENV_IS_TESTING) {
    $di = new Phalcon\Di\FactoryDefault\Cli();
} else {
    $di = new Phalcon\Di();
    $di->setShared('router', "Phalcon\\Mvc\\Router");
    $di->setShared('response', "Phalcon\\Http\\Response");
    $di->setShared('request', "Phalcon\\Http\\Request");
    $di->setShared('modelsManager', "Phalcon\\Mvc\\Model\\Manager");
    $di->setShared('modelsMetadata', "Phalcon\\Mvc\\Model\\MetaData\\Memory");
    $di->setShared('filter', "Phalcon\\Filter");
}

if (APP_ENV_IS_DEV) {
    $di->setShared('eventsManager', "Phalcon\\Events\\Manager");
}

$di->set('db', function () use ($config) {
    $connection = Phalcon\Db\Adapter\Pdo\Factory::load($config->database);
    if (APP_ENV_IS_DEV) {
        $em = container('eventsManager');
        $em->attach('db', new Logger\Listener\Database());
        $connection->setEventsManager($em);
    }
    return $connection;
});

$di->set('logger', function () use ($config) {
    $formatter = new Phalcon\Logger\Formatter\Line($config->logger->format);
    $level = $config->logger->level;
    $logger = Phalcon\Logger\Factory::load($config->logger);
    $logger->setFormatter($formatter);
    $logger->setLogLevel($level);
    return $logger;
});

$di->set('queue', function () use ($config) {
    return new PhpAmqpLib\Connection\AMQPStreamConnection(
        $config->queue->host,
        $config->queue->port,
        $config->queue->username,
        $config->queue->password
    );
});
