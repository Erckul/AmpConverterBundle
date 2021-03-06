<?php

namespace Elephantly\AmpConverterBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('elephantly_amp_converter');

        $rootNode
            ->children()
                ->arrayNode('img')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('amp_anim')->defaultValue(true)->end()
                    ->end()
                ->end()
                ->arrayNode('pinterest')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('follow_label')->defaultValue('Follow us')->end()
                    ->end()
                ->end()
                ->arrayNode('iframe')
                ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('sandbox')->defaultValue('allow-scripts allow-same-origin')->end()
                    ->end()
                ->end()
                ->arrayNode('illegal_tags')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->arrayNode('illegal_attributes')
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->arrayNode('illegal_tag_attributes')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->prototype('scalar')
                        ->end()
                    ->end()
                ->end()
                ->booleanNode('remove_incorrect_tags')->defaultValue(true)->end()
                ->booleanNode('replace_incorrect_tags')->defaultValue(true)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
