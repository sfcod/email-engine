<?php

namespace SfCod\EmailEngineBundle\DependencyInjection;

use Psr\Log\LoggerInterface;
use SfCod\EmailEngineBundle\Mailer\Mailer;
use SfCod\EmailEngineBundle\Mailer\TemplateManager;
use SfCod\EmailEngineBundle\Template\ParametersAwareInterface;
use SfCod\EmailEngineBundle\Template\Params\ParameterResolver;
use SfCod\EmailEngineBundle\Template\Params\ParameterResolverInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class EmailEngineExtension
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\DependencyInjection
 */
class EmailEngineExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new EmailEngineConfiguration();

        $config = $this->processConfiguration($configuration, $configs);

        $this->createSenders($config, $container);
        $this->createTemplates($config, $container);
        $this->createResolver($config, $container);
    }

    /**
     * Get extension alias
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'sfcod_email_engine';
    }

    /**
     * Create parameter resolver
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function createResolver(array $config, ContainerBuilder $container)
    {
        $resolver = new Definition(ParameterResolverInterface::class);
        $resolver->setPublic(true);
        $resolver->setClass(ParameterResolver::class);
        $resolver->setArguments([
            new Reference(ContainerInterface::class),
        ]);

        $container->setDefinition(ParameterResolverInterface::class, $resolver);
    }

    /**
     * Create senders
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function createSenders(array $config, ContainerBuilder $container)
    {
        $configuration = [];
        $mainSender = $this->getSender($config['main_sender'], $config);

        if (isset($mainSender['chain'])) {
            foreach ($mainSender['chain']['senders'] as $sender) {
                $configuration[$sender] = $this->getSender($sender, $config);
            }
        } else {
            $configuration[$config['main_sender']] = $mainSender;
        }

        $senders = [];
        $repositories = [];
        foreach ($configuration as $senderName => $senderConfig) {
            if (false === isset($senderConfig['sender'], $senderConfig['repository']) ||
                false === isset($senderConfig['sender']['class'], $senderConfig['repository']['class'])) {
                throw new InvalidConfigurationException(sprintf('"sender" and "repository" must be defined in "%s" sender.', $senderName));
            }

            $sender = new Definition($senderConfig['sender']['class']);
            $sender
                ->setPublic(true)
                ->setAutoconfigured(true)
                ->setAutowired(true);

            $repository = new Definition($senderConfig['repository']['class']);
            $repository
                ->setPublic(true)
                ->setAutoconfigured(true)
                ->setAutowired(true);

            $container->addDefinitions([
                $senderConfig['repository']['class'] => $repository,
                $senderConfig['sender']['class'] => $sender,
            ]);

            // Prepare senders and repositories for injecting into mailer
            $senders[$senderConfig['sender']['class']] = $sender;
            $repositories[$senderConfig['repository']['class']] = $repository;
        }

        $mailer = new Definition(Mailer::class);
        $mailer
            ->setPublic(true)
            ->setArguments([
                new Reference(ParameterResolverInterface::class),
                new Reference(LoggerInterface::class),
            ])
            ->addMethodCall('setConfiguration', [$configuration])
            ->addMethodCall('setSenders', [$senders])
            ->addMethodCall('setRepositories', [$repositories]);

        $container->setDefinition(Mailer::class, $mailer);
    }

    /**
     * Get sender from senders config
     *
     * @param string $sender
     * @param array $config
     *
     * @return array
     */
    private function getSender(string $sender, array $config): array
    {
        if (false === isset($config['senders'][$sender])) {
            throw new InvalidConfigurationException(sprintf('Main sender "%s" does not exist in senders "%s".', $sender, json_encode(array_keys($config['senders']))));
        }

        return $config['senders'][$sender];
    }

    /**
     * Create templates
     *
     * @param array $config
     * @param ContainerBuilder $container
     */
    private function createTemplates(array $config, ContainerBuilder $container)
    {
        foreach ($config['templates'] as $template) {
            if (in_array(ParametersAwareInterface::class, class_implements($template))) {
                /** @var ParametersAwareInterface $template */
                foreach ($template::listParameters() as $parameter) {
                    $definition = new Definition($parameter);
                    $definition
                        ->setPublic(true)
                        ->setAutowired(true)
                        ->setAutoconfigured(true)
                        ->addTag(sprintf('%s.parameter', $template));

                    $container->setDefinition($parameter, $definition);
                }
            }
        }

        $templateManager = new Definition(TemplateManager::class);
        $templateManager
            ->setPublic(true)
            ->addArgument($config['templates']);

        $container->setDefinition(TemplateManager::class, $templateManager);
    }
}
