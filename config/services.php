<?php
/** @var \Phalcon\Config $config */

$di = new Phalcon\Di\FactoryDefault();

$di->setShared('view', function () use ($config) {
    $view = new Phalcon\Mvc\View\Simple();
    $view->setViewsDir($config->application->viewsDir);
    return $view;
});

$di->set('url', function () use ($config) {
    $url = new Phalcon\Mvc\Url();
    $url->setBasePath(BASE_PATH . DIRECTORY_SEPARATOR);
    $url->setBaseUri($config->application->baseUri);
    $url->setStaticBaseUri($config->application->staticUrl);
    return $url;
});

$di->set('db', function () use ($config) {
    $em = container('eventsManager');
    $connection = Phalcon\Db\Adapter\Pdo\Factory::load($config->database);
    $em->attach('db', new Logger\Listener\Database());
    $connection->setEventsManager($em);
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
