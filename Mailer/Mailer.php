<?php

namespace SfCod\EmailEngineBundle\Mailer;

use SfCod\EmailEngineBundle\Exception\RepositoryUnavailableException;
use SfCod\EmailEngineBundle\Repository\RepositoryInterface;
use SfCod\EmailEngineBundle\Sender\SenderInterface;
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
     *
     * @return int
     */
    public function send(TemplateInterface $template, $emails): int
    {
        $sentCount = 0;

        foreach ((array)$emails as $email) {
            if (!$email) {
                continue;
            }

            foreach ($this->senders as $config) {
                try {
                    $concreteTemplate = clone $template;
                    $concreteSender = $this->makeSender($config['sender']);

                    if ($concreteTemplate instanceof ContainerAwareInterface) {
                        $concreteTemplate->setContainer($this->container);
                    }

                    if ($concreteTemplate instanceof RepositoryAwareInterface) {
                        $concreteTemplate->setRepository($this->makeRepository($config['repository'], $concreteTemplate));
                    }

                    if ($concreteSender->send($concreteTemplate, $email)) {
                        ++$sentCount;

                        break;
                    }
                } catch (RepositoryUnavailableException $e) {
                    if ('dev' === $this->container->getParameter('kernel.environment')) {
                        $this->container->get('logger')->error($e);
                    }

                    // Try next sender
                }
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
        $sender = new $config['class']();

        if ($sender instanceof ContainerAwareInterface) {
            $sender->setContainer($this->container);
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
        $repository = new $config['class']($this->container, $template, $config['arguments']);

        return $repository;
    }
}
