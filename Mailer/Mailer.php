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
     * Configuration with ordered senders
     *
     * @var array
     */
    private $configuration = [];

    /**
     * Senders for email sending
     *
     * @var SenderInterface[]
     */
    private $senders = [];

    /**
     * Repositories list for senders
     *
     * @var RepositoryInterface[]
     */
    private $repositories = [];

    /**
     * @var ParameterResolverInterface
     */
    private $parameterResolver;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * EmailSender constructor.
     *
     * @param ParameterResolverInterface $parameterResolver
     * @param LoggerInterface $logger
     */
    public function __construct(ParameterResolverInterface $parameterResolver, LoggerInterface $logger)
    {
        $this->parameterResolver = $parameterResolver;
        $this->logger = $logger;
    }

    /**
     * Set senders
     *
     * @param array $senders
     */
    public function setSenders(array $senders)
    {
        $this->senders = $senders;
    }

    /**
     * @param RepositoryInterface[] $repositories
     */
    public function setRepositories(array $repositories)
    {
        $this->repositories = $repositories;
    }

    /**
     * @param array $configuration
     */
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
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

        foreach ($this->configuration as $config) {
            try {
                $concreteTemplate = clone $template;
                $sender = array_merge($config['sender'], ['options' => $options]);
                $concreteSender = $this->makeSender($sender['class'], $sender['options']);

                if ($concreteTemplate instanceof RepositoryAwareInterface) {
                    $concreteTemplate->setRepository($this->makeRepository($config['repository']['class'], $concreteTemplate, $config['repository']['arguments']));
                }

                if ($concreteTemplate instanceof ParametersAwareInterface) {
                    $concreteTemplate->setParameterResolver($this->parameterResolver);
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
     * @param string $senderName
     * @param MessageOptionsInterface|null $options
     *
     * @return SenderInterface
     */
    protected function makeSender(string $senderName, ?MessageOptionsInterface $options = null): SenderInterface
    {
        /** @var SenderInterface $sender */
        $sender = $this->senders[$senderName];

        if ($options) {
            $sender->setOptions($options);
        }

        return $sender;
    }

    /**
     * Make email engine repository
     *
     * @param string $repositoryName
     * @param TemplateInterface $template
     * @param array $arguments
     *
     * @return RepositoryInterface
     */
    protected function makeRepository(string $repositoryName, TemplateInterface $template, array $arguments = []): RepositoryInterface
    {
        /** @var RepositoryInterface $repository */
        $repository = $this->repositories[$repositoryName];
        $repository->connect($template, $arguments);

        return $repository;
    }
}
