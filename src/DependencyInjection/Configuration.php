<?php declare(strict_types=1);

namespace Palmyr\GitHooks\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('hooks');

        $treeBuilder
            ->getRootNode()
                ->children()
                    ->scalarNode('log_file')
                    ->end()
                    ->arrayNode('hooks')
                        ->normalizeKeys(false)
                        ->arrayPrototype()
                            ->arrayPrototype()
                                ->children()
                                    ->arrayNode('branches')
                                        ->beforeNormalization()
                                            ->castToArray()
                                        ->end()
                                        ->scalarPrototype()
                                        ->end()
                                    ->end()
                                    ->arrayNode('commands')
                                        ->scalarPrototype()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}