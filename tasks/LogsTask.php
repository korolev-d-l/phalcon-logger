<?php

use Phalcon\Cli\Task;

class LogsTask extends Task
{
    /**
     * Run consumer for save log with queue
     * -v - verbose
     * -t - transaction
     * @param array $params
     */
    public function queueAction(array $params)
    {
        $verbose     = in_array('-v', $params);
        $transaction = in_array('-t', $params);

        /** @var PhpAmqpLib\Connection\AMQPStreamConnection $connection */
        $connection = $this->getDI()->getShared('queue');
        $channel = $connection->channel();
        $channel->queue_declare(LogsQueue::class, false, true, false, false);

        /** @param \PhpAmqpLib\Message\AMQPMessage $msg */
        $callback = function($msg) use ($verbose, $transaction)
        {
            $logEntry = json_decode($msg->body, true);
            if ($transaction) {
                $this->getDI()->getShared('db')->begin();
            }
            $log = new Logs();
            if ($log->save($logEntry) === false) {
                if ($transaction) {
                    $this->getDI()->getShared('db')->rollback();
                }
                throw new \RuntimeException('Error save logs.');
            }
            echo var_dump($log);
            if ($transaction) {
                $this->getDI()->getShared('db')->commit();
            }
            if ($verbose) {
                fwrite(STDOUT, 'SAVE: ' . $msg->body . PHP_EOL);
            }
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };
        $channel->basic_qos(null, 1, null);
        $channel->basic_consume(LogsQueue::class, '', false, false, false, false, $callback);

        /**
         * @param \PhpAmqpLib\Channel\AMQPChannel $channel
         * @param \PhpAmqpLib\Connection\AbstractConnection $connection
         */
        $shutdown = function ($channel, $connection)
        {
            $channel->close();
            $connection->close();
        };
        register_shutdown_function($shutdown, $channel, $connection);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }
}