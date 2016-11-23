<?php

/*
 * This file is part of SMSAPIBundle
 *
 * (c) Krystian Karaś <k4rasq@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Cogitech\SMSAPIBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('cogitech_smsapi');

        $rootNode
            ->children()
                ->arrayNode('authentication')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('token')->end()
                        ->scalarNode('login')->end()
                        ->scalarNode('password')->end()
                    ->end()
                   ->end()
                   ->arrayNode('default_values')
                       ->addDefaultsIfNotSet()
                       ->children()
                           ->scalarNode('sender')
                           ->defaultValue('')
                       ->end()
                   ->end()
               ->end();

        return $treeBuilder;
    }
}
