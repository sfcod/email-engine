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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * EmailSender constructor.
     *
     * @param ContainerInterface $container
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
     * Set Logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
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
                $sender = array_merge($config['sender'], ['options' => $options]);
                $concreteSender = $this->makeSender($sender['class'], $sender['options']);

                if ($concreteTemplate instanceof RepositoryAwareInterface) {
                    $concreteTemplate->setRepository($this->makeRepository($config['repository']['class'], $concreteTemplate, $config['repository']['arguments']));
                }

                if ($concreteTemplate instanceof ParametersAwareInterface) {
                    $concreteTemplate->setParameterResolver($this->container->get(ParameterResolverInterface::class));
                }

                if ($concreteSender->send($concreteTemplate, $emails)) {
                    ++$sentCount;

                    break;
                }
            } catch (RepositoryUnavailableException $e) {
                $this->logger->error($e->getMessage(), ['exception' => $e]);

                // Try next sender
            }
        }

        return $sentCount;
    }

    /**
     * Make email engine sender
     *
     * @param string $sender
     * @param MessageOptionsInterface|null $options
     *
     * @return SenderInterface
     */
    protected function makeSender(string $sender, ?MessageOptionsInterface $options = null): SenderInterface
    {
        /** @var SenderInterface $sender */
        $sender = $this->container->get($sender);

        if ($options) {
            $sender->setOptions($options);
        }

        return $sender;
    }

    /**
     * Make email engine repository
     *
     * @param string $repository
     * @param TemplateInterface $template
     * @param array $arguments
     *
     * @return RepositoryInterface
     */
    protected function makeRepository(string $repository, TemplateInterface $template, array $arguments = []): RepositoryInterface
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->container->get($repository);
        $repository->connect($template, $arguments);

        return $repository;
    }
}
