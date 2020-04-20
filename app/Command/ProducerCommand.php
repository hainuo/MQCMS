<?php

declare(strict_types=1);

namespace App\Command;

use App\Amqp\Producer\DemoProducer;
use Hyperf\Amqp\Producer;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;

/**
 * @Command
 */
class ProducerCommand extends HyperfCommand
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct('producer');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Hyperf Producer Command');
    }

    public function handle()
    {
        $this->output->writeln(sprintf('<fg=green>%s</>', '生产者开始监听...'));

        $message = new DemoProducer(['name' =>'hello-' . time(), 'a' => 1]);
        $producer = ApplicationContext::getContainer()->get(Producer::class);
        $result = [];
        for ($i = 0; $i < 10; $i++) {
            $result[] = $producer->produce($message);
        }
        $this->output->writeln(sprintf('<fg=green>%s</>', 'result：' . json_encode($result)));

    }
}
