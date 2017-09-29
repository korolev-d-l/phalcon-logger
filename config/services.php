<?php
/** @var \Phalcon\Config $config */

if (PHP_SAPI === 'cli') {
    $di = new Phalcon\Di\FactoryDefault\Cli();
} else {
    $di = new Phalcon\Di\FactoryDefault();

    $di->setShared('view', function () use ($config) {
        $view = new Phalcon\Mvc\View\Simple();
        $view->setViewsDir($config->application->viewsDir);
        return $view;
    });
}

$di->set('url', function () use ($config) {
    $url = new Phalcon\Mvc\Url();
    $url->setBasePath(BASE_PATH . DIRECTORY_SEPARATOR);
    $url->setBaseUri($config->application->baseUri);
    $url->setStaticBaseUri($config->application->staticUrl);
    return $url;
});

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
