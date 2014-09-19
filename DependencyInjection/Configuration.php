<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekyna_core');

        $rootNode
            ->children()
                ->scalarNode('output_dir')->defaultValue('')->end()
                ->scalarNode('new_image_path')->defaultValue('/bundles/ekynacore/img/new-image.gif')->end()
                ->arrayNode('chain_router')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('enable')->defaultTrue()->end()
                        ->arrayNode('routers_by_id')
                            ->defaultValue(array('router.default' => 100))
                            ->useAttributeAsKey('id')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
