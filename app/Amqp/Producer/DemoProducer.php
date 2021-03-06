<?php

declare(strict_types=1);

namespace App\Amqp\Producer;

use Hyperf\Amqp\Annotation\Producer;
use Hyperf\Amqp\Builder\ExchangeBuilder;
use Hyperf\Amqp\Message\ProducerMessage;

/**
 * @Producer(exchange="demo", routingKey="demo")
 */
class DemoProducer extends ProducerMessage
{
    public function __construct($data)
    {
        $this->payload = $data;
    }
    //
    // public function getExchangeBuilder(): ExchangeBuilder
    // {
    //     return parent::getExchangeBuilder()->setDurable(false); // TODO: Change the autogenerated stub
    // }
}
