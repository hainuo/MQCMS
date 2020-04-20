<?php

declare(strict_types=1);

namespace App\Command;

use App\Amqp\Consumer\BaseConsumer;
use App\Amqp\Consumer\DemoConsumer;
use Hyperf\Amqp\Consumer;
use Hyperf\Amqp\Pool\PoolFactory;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Utils\ApplicationContext;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class ConsumerCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct('consumer');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Consumer Command');
    }

    public function handle()
    {
        $this->output->writeln(sprintf('<fg=green>%s</>', '消费者开始监听...'));

        // $customer = new DemoConsumer();
        // $consumer = ApplicationContext::getContainer()->get(Consumer::class);
        // $result = $consumer->consume($customer);

        // 如果配置 isEnable 为 false，不跟随服务启动，可通过Commmand手动处理消息
        // $consumer = new Consumer(
        //     $this->container,
        //     $this->container->get(\Hyperf\Amqp\Pool\PoolFactory::class),
        //     $this->container->get(\Hyperf\Contract\StdoutLoggerInterface::class)
        // );
        // $result = $consumer->consume(new DemoConsumer());

        // $pool = $this->getConnectionPool($consumerMessage->getPoolName());
        // /** @var \Hyperf\Amqp\Connection $connection */
        // $connection = $pool->get();
        // $channel = $connection->getConfirmChannel();
        //
        // $connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest', '/');
        // $channel = $connection->channel();
        // $channel->queue_declare('hyperf', false, true, false, false);
        //
        // $callback = function ($msg) {
        //     echo 'received：' . $msg->body . PHP_EOL;
        // };
        // $channel->basic_consume('hyperf', '', false, true, false, false, $callback);
        //
        // while ($channel->is_consuming()) {
        //     $channel->wait();
        // }
        // $channel->close();
        // $connection->close();

        $consumer = new BaseConsumer(
            $this->container,
            $this->container->get(PoolFactory::class),
            $this->container->get(StdoutLoggerInterface::class)
        );
        $consumer->consume(new DemoConsumer());
        $this->output->writeln(sprintf('<fg=green>%s</>', 'success'));

    }
}
