<?php

namespace SfCod\EmailEngineBundle\Mailer;

use Psr\Log\LoggerInterface;
use SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException;
use SfCod\EmailEngineBundle\Repository\RepositoryInterface;
use SfCod\EmailEngineBundle\Sender\MessageOptionsInterface;
use SfCod\EmailEngineBundle\Sender\SenderInterface;
use SfCod\EmailEngineBundle\Template\ParametersAwareInterface;
use SfCod\EmailEngineBundle\Template\Params\ParameterResolverInterface;
use SfCod\EmailEngineBundle\Template\RepositoryAwareInterface;
use SfCod\EmailEngineBundle\Template\TemplateInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Mailer
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\Sender
 */
class Mailer
{
    /**
     * Senders for email sending
     *
     * @var SenderInterface[]
     */
    private $senders = [];

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * EmailSender constructor.
     *
     * @param ContainerInterface $container
     * @param LoggerInterface $logger
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get senders
     *
     * @return array
     */
    public function getSenders(): array
    {
        return $this->senders;
    }

    /**
     * Set senders
     *
     * @param array $senders
     */
    public function setSenders(array $senders): void
    {
        $this->senders = $senders;
    }

    /**
     * Send email template
     *
     * @param TemplateInterface $template
     * @param array|string $emails
     * @param null|MessageOptionsInterface $options
     *
     * @return int
     */
    public function send(TemplateInterface $template, $emails, ?MessageOptionsInterface $options = null): int
    {
        $sentCount = 0;

        foreach ($this->senders as $config) {
            try {
                $concreteTemplate = clone $template;
                $concreteSender = $this->makeSender(array_merge($config['sender'], ['options' => $options]));

                if ($concreteTemplate instanceof RepositoryAwareInterface) {
                    $concreteTemplate->setRepository($this->makeRepository($config['repository'], $concreteTemplate));
                }

                if ($concreteTemplate instanceof ParametersAwareInterface) {
                    $concreteTemplate->setParameterResolver($this->container->get(ParameterResolverInterface::class));
                }

                if ($concreteSender->send($concreteTemplate, $emails)) {
                    ++$sentCount;

                    break;
                }
            } catch (RepositoryUnavailableException $e) {
                if ($this->container->get('kernel')->isDebug()) {
                    $this->container->get(LoggerInterface::class)->error($e->getMessage(), ['exception' => $e]);
                }

                // Try next sender
            }
        }

        return $sentCount;
    }

    /**
     * Make email engine sender
     *
     * @param array $config
     *
     * @return SenderInterface
     */
    protected function makeSender(array $config): SenderInterface
    {
        /** @var SenderInterface $sender */
        $sender = $this->container->get($config['class']);

        if ($config['options']) {
            $sender->setOptions($config['options']);
        }

        return $sender;
    }

    /**
     * Make email engine repository
     *
     * @param array $config
     * @param TemplateInterface $template
     *
     * @return RepositoryInterface
     */
    protected function makeRepository(array $config, TemplateInterface $template): RepositoryInterface
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->container->get($config['class']);
        $repository->initialize($template, $config['arguments']);

        return $repository;
    }
}
