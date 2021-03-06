<?php
/*
 * This file is part of the RedisBundle package.
 *
 * (c) Axel Etcheverry <axel@etcheverry.biz>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Euskadi31\Bundle\RedisBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('euskadi31_redis');

        $rootNode
            ->children()
                ->scalarNode('type')
                    ->isRequired()
                    ->defaultValue('redis')
                ->end()
                ->arrayNode('server')
                    ->children()
                        ->scalarNode('host')->defaultValue('localhost')->end()
                        ->scalarNode('port')->defaultValue(6379)->end()
                    ->end()
                ->end()
                ->arrayNode('sentinels')
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('host')->isRequired()->end()
                            ->scalarNode('port')->defaultValue(26379)->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('client')
                    ->isRequired()
                    ->children()
                        ->arrayNode('redis')
                            ->isRequired()
                            ->children()
                                ->integerNode('retry')->defaultValue(0)->end()
                                ->integerNode('interval')->defaultValue(1000)->end()
                                ->scalarNode('auth')->defaultNull()->end()
                                ->scalarNode('namespace')->defaultNull()->end()
                                ->integerNode('db')
                                    ->min(0)
                                ->end()
                                ->floatNode('timeout')
                                    ->defaultValue(1)
                                    ->min(0)
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('sentinel')
                            ->children()
                                ->scalarNode('master')->isRequired()->end()
                                ->scalarNode('auth')->defaultNull()->end()
                                ->scalarNode('namespace')->defaultNull()->end()
                                ->integerNode('db')
                                    ->min(0)
                                ->end()
                                ->floatNode('timeout')
                                    ->defaultValue(0.5)
                                    ->min(0)
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
