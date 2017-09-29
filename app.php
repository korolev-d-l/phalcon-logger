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
    echo "PHALCON LOGGER V0.2";
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
    $isQueue  = $app->request->getQuery('queue');

    $response = new Response();
    $response->setContentType('application/json', 'UTF-8');

    if ($logEntry === false || $logEntry === null) {
        $errMessage = $logEntry === null ? json_last_error_msg() : 'Error reading data';
        $response->setStatusCode(400);
        $response->setJsonContent([
            'result' => 'error',
            'errors' => [$errMessage]
        ]);

        return $response;
    }

    $log = $isQueue ? new LogsQueue() : new Logs();

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
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found', true, 404);
    echo '404 Not Found' . PHP_EOL;
});

$app->error(function (\Exception $e) use ($app) {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
    echo '500 Internal Server Error' . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
});
//$app->error(function ($exception) {
//    echo json_encode([
//            'code'    => $exception->getCode(),
//            'status'  => 'error',
//            'message' => $exception->getMessage(),
//    ]);
//});


//micro:beforeHandleRoute
//micro:beforeExecuteRoute
//$app->before(function () use ($app) {
//    $isStop = $app->request->getHeader('X-API-KEY') !== '6fa741de1bdd1d91830ba';
//    if ($isStop) {
//        $app->stop();
//    }
//    return !$isStop;
//});
//$app->before(new class implements \Phalcon\Mvc\Micro\MiddlewareInterface {
//    public function call(\Phalcon\Mvc\Micro $app)
//    {
//        $isStop = $app->request->getHeader('X-API-KEY') !== '6fa741de1bdd1d91830ba';
//        if ($isStop) {
//            $app->stop();
//        }
//        return !$isStop;
//    }
//});

//$app->getSharedService('eventsManager')->attach(
//    'micro:beforeExecuteRoute',
###
//ACL
###
//$whitelist = [
//    '10.4.6.1',
//    '10.4.6.2',
//    '10.4.6.3',
//    '10.4.6.4',
//];
//$ipAddress = $application->request->getClientAddress();
//
//if (true !== array_key_exists($ipAddress, $whitelist)) {
//    $this->response->redirect('/401');
//    $this->response->send();
//
//    return false;
//}
//
//return true;
###
//    function (Phalcon\Events\Event $event, $app) {
//        if ($app->request->getHeader('X-API-KEY') !== '6fa741de1bdd1d91830ba') {
//
//            return false;
//        }
//    }
//);

//$app->after(
//    function () use ($app) {
//$payload = [
//    'code'    => 200,
//    'status'  => 'success',
//    'message' => '',
//    'payload' => $application->getReturnedValue(),
//];
//
//$application->response->setJsonContent($payload);
//$application->response->send();
//
//return true;
//        $response = new Response();
//        $response->setContentType('application/json', 'UTF-8');
//        $response->setJsonContent($app->getReturnedValue());
//        $response->send();
//        return true;
//    }
//);