<?php

use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;
use Phalcon\Mvc\Model;

Model::setup(
    [
        'columnRenaming'       => false,
        'disableAssignSetters' => true,
        'updateSnapshotOnSave' => false,
        'virtualForeignKeys'   => false,
        'notNullValidations'   => false,
        'events'               => true,
    ]
);

$app->get('/', function () use ($app) {
    echo $app->getService('view')->render('index');
});

$app->get('/api/logs', function () use ($app) {
    $response = new Response();
    $response->setContentType('application/json', 'UTF-8');

    $dateFrom = $app->request->getQuery('from');
    $dateTo   = $app->request->getQuery('to', null, date('Y-m-d H:i:s'));
    $entity   = $app->request->getQuery('entity');
    $entityId = $app->request->getQuery('entityId', 'int');
    $action   = $app->request->getQuery('action');
    $userId   = $app->request->getQuery('userId', 'int');

    $currentPage = $app->request->getQuery('page', null, 1);

    $logsQuery = $app->modelsManager->createBuilder()
        ->from('Logs');

    if ($dateFrom && $dateTo) {
        $logsQuery->andWhere("date >= :from:",  ['from' => $dateFrom])
            ->andWhere("date <= :to:", ['to' => $dateTo]);
    }

    if ($entity) {
        $logsQuery->andWhere("entity = :entity:", ['entity' => $entity]);
    }

    if ($entityId) {
        $entityIds = is_array($entityId) ? array_values($entityId) : [$entityId];
        $logsQuery->inWhere("entityId", $entityIds);
    }

    if ($action) {
        $logsQuery->andWhere("action = :action:", ['action' => $action]);
    }

    if ($userId) {
        $userIds = is_array($userId) ? array_values($userId) : [$userId];
        $logsQuery->inWhere("userId", $userIds);
    }

    $paginator = new PaginatorQueryBuilder([
        "builder" => $logsQuery,
        "limit"   => 10,
        "page"    => $currentPage
    ]);

    $response->setJsonContent($paginator->getPaginate());

    return $response;
});

$app->post('/api/logs', function () use ($app) {
    $logEntry = $app->request->getJsonRawBody(true);

    $response = new Response();
    $response->setContentType('application/json', 'UTF-8');

    $log = new Logs();

    if ($log->save($logEntry) === false) {
        $response->setStatusCode(400);
        $response->setJsonContent([
            'result' => 'error',
            'errors' => array_map('strval', $log->getMessages())
        ]);

        return $response;
    }

    $response->setJsonContent(['result' => 'ok']);

    return $response;
});

$app->notFound(function () use ($app) {
    $app->response->setStatusCode(404, "Not Found")->sendHeaders();
    echo $app->getService('view')->render('404');
});

$app->error(function () use ($app) {
    $app->response->setStatusCode(500, "Internal Server Error")->sendHeaders();
    echo $app->getService('view')->render('500');
});



