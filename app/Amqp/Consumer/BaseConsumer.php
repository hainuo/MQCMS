<?php

namespace App\Amqp\Consumer;

use Hyperf\Amqp\Consumer;
use Hyperf\Amqp\Message\ConsumerMessageInterface;
use Hyperf\Utils\Coroutine\Concurrent;
use PhpAmqpLib\Message\AMQPMessage;

class BaseConsumer extends Consumer
{
    public function consume(ConsumerMessageInterface $consumerMessage): void
    {
        $pool = $this->getConnectionPool($consumerMessage->getPoolName());
        /** @var \Hyperf\Amqp\Connection $connection */
        $connection = $pool->get();
        $channel = $connection->getConfirmChannel();

        $this->declare($consumerMessage, $channel);
        $concurrent = $this->getConcurrent();

        $channel->basic_consume(
            $consumerMessage->getQueue(),
            $consumerMessage->getConsumerTag(),
            false,
            false,
            false,
            false,
            function (AMQPMessage $message) use ($consumerMessage, $concurrent) {
                $callback = $this->getCallback($consumerMessage, $message);
                if (! $concurrent instanceof Concurrent) {
                    return parallel([$callback]);
                }

                return $concurrent->create($callback);
            }
        );

        while (count($channel->callbacks) > 0) {
            $channel->wait();
        }

        $pool->release($connection);
    }
}