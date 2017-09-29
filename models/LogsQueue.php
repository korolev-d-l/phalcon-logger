<?php

use PhpAmqpLib\Message\AMQPMessage;

class LogsQueue extends Logs
{

    public function save($data = null, $whiteList = null)
    {
        if (is_array($data) && count($data) > 0) {
            $this->assign($data, null, $whiteList);
		}

        $this->_errorMessages = [];

        if ($this->validation() === false) {
            return false;
        }

        /** @var PhpAmqpLib\Connection\AMQPStreamConnection $connection */
        $connection = $this->getDI()->get('queue');

        $channel = $connection->channel();
        $channel->queue_declare(static::class, false, true, false, false);
        $msg = new AMQPMessage(json_encode($this), [
            'content_type'  => 'application/json',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);
        $channel->basic_publish($msg, '', static::class);
        $channel->close();

        $connection->close();

        return true;
    }
}
