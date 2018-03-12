<?php

namespace SfCod\EmailEngineBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class EmailEngineConfiguration
 *
 * @author Virchenko Maksim <muslim1992@gmail.com>
 *
 * @package SfCod\EmailEngineBundle\DependencyInjection
 */
class EmailEngineConfiguration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sfcod_email_engine');

        $this->addMainSender($rootNode);
        $this->addSendersSection($rootNode);
        $this->addTemplatesSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Add main sender node
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addMainSender(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('main_sender')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
            ->end();
    }

    /**
     * Add senders section
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addSendersSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('senders')
                    ->example([
                        'chained_sender' => [
                            'chain' => [
                                'senders' => [
                                    'first_sender',
                                    'second_sender',
                                ],
                            ],
                        ],
                        'first_sender' => [
                            'sender' => ['class' => 'SenderClass'],
                            'repository' => ['class' => 'RepositoryClass', 'arguments' => []],
                        ],
                        'second_sender' => [
                            'sender' => ['class' => 'SenderClass'],
                            'repository' => ['class' => 'RepositoryClass', 'arguments' => []],
                        ],
                    ])
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                    ->children()
                        ->arrayNode('sender')
                            ->children()
                                ->scalarNode('class')->isRequired()->end()
                            ->end()
                        ->end()
                        ->arrayNode('repository')
                            ->children()
                                ->scalarNode('class')->isRequired()->end()
                                ->arrayNode('arguments')
                                    ->defaultValue([])
                                    ->scalarPrototype()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->children()
                        ->scalarNode('class')->end()
                            ->arrayNode('chain')
                                ->children()
                                    ->arrayNode('senders')
                                        ->scalarPrototype()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Add templates section
     *
     * @param ArrayNodeDefinition $rootNode
     */
    private function addTemplatesSection(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('templates')
                    ->defaultValue([])
                    ->scalarPrototype()->end()
                ->end()
            ->end();
    }
}
