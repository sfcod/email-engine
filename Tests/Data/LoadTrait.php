<?php

namespace SfCod\EmailEngineBundle\Tests\Data;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use SfCod\EmailEngineBundle\DependencyInjection\EmailEngineExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Dotenv\Exception\PathException;

/**
 * Trait LoadTrait
 *
 * @package SfCod\QueueBundle\Tests\Data
 */
trait LoadTrait
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Configure container
     *
     * @throws \ReflectionException
     */
    protected function configure(string $sender, string $repository)
    {
        $dotenv = new Dotenv();
        try {
            $dotenv->load(__DIR__ . '/../../.env');
        } catch (PathException $e) {
            // Nothing
        }

        $extension = new EmailEngineExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.project_dir', getenv('KERNEL_PROJECT_DIR'));
        $container->setParameter('kernel.root_dir', realpath(__DIR__ . '/../../../../SfCod/'));
        $container->set(LoggerInterface::class, new Logger('test'));
        $container->set(\Swift_Mailer::class, new \Swift_Mailer(new \Swift_Transport_NullTransport(new \Swift_Events_SimpleEventDispatcher())));

        $extension->load([
            [
                'main_sender' => 'default',
            ],
            [
                'senders' => [
                    'default' => [
                        'sender' => [
                            'class' => $sender,
                        ],
                        'repository' => [
                            'class' => $repository,
                        ],
                    ],
                ],
            ],
            [
                'templates' => [
                    'SfCod\EmailEngineBundle\Example\TestEmailTemplate',
                ],
            ],
        ], $container);

        $this->container = $container;
    }
}
