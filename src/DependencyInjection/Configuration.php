<?php

namespace Paysera\Bundle\MabaWebpackExtensionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('paysera_maba_webpack_extension');

        $rootNode->children()
            ->arrayNode('replace_paths')
                ->defaultValue([])
                ->prototype('scalar')->end()
            ->end()
            ->arrayNode('replace_items')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode('webpack_config_path')->defaultTrue()->end()
                    ->booleanNode('alias')->defaultTrue()->end()
                    ->booleanNode('manifest_path')->defaultTrue()->end()
                    ->booleanNode('entry')->defaultFalse()->end()
                ->end()
            ->end()
            ->scalarNode('replaced_webpack_config_path')
                ->defaultValue('%kernel.cache_dir%/webpack.config_%kernel.environment%.js')
            ->end()
        ->end();

        return $treeBuilder;
    }
}
