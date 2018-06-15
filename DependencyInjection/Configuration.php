<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
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
                ->append($this->getUiNode())
                ->append($this->getRouterNode())
                ->append($this->getCacheNode())
                ->append($this->getSwiftMailerNode())
            ->end()
        ;

        return $treeBuilder;
    }

    private function getCacheNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('cache');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enable')->defaultValue('%reverse_proxy.enable%')->end()
                ->integerNode('default_smaxage')->defaultValue(3600)->end()
                ->arrayNode('tag')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('secret')->defaultValue('%secret%')->end()
                        ->booleanNode('encode')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    private function getRouterNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('chain_router');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('routers_by_id')
                    ->useAttributeAsKey('id')
                    ->scalarPrototype()->end()
                    ->defaultValue(['router.default' => 1024])
                ->end()
            ->end()
        ;

        return $node;
    }

    private function getUiNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('ui');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('controls_template')->defaultValue('EkynaCoreBundle:Ui:controls.html.twig')->end()
                ->scalarNode('no_image_path')->defaultValue('/bundles/ekynacore/img/new-image.gif')->end()
                ->variableNode('locales')->defaultValue('%locales%')->end()
                ->arrayNode('tinymce')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->enumNode('base_formats')
                            ->values([null, 'default', 'bootstrap'])
                            ->defaultNull()
                        ->end()
                        ->variableNode('custom_formats')
                            ->defaultNull()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('stylesheets')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('contents')
                            ->treatNullLike([])
                            ->defaultValue(['bundles/ekynacore/css/content.css'])  // TODO Remove value and file
                            ->scalarPrototype()->cannotBeEmpty()->end()
                        ->end()
                        ->arrayNode('forms')
                            ->treatNullLike([])
                            ->defaultValue([])
                            ->scalarPrototype()->cannotBeEmpty()->end()
                        ->end()
                        ->arrayNode('fonts')
                            ->treatNullLike([])
                            ->defaultValue([])
                            ->scalarPrototype()->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('colors')
                    ->useAttributeAsKey('name')
                    ->scalarPrototype()
                        ->cannotBeEmpty()
                        ->validate()
                            ->ifTrue(function($value) {
                                return !preg_match('~^[A-Fa-f0-9]{6}|[A-Fa-f0-9]{3}$~', $value);
                            })
                            ->thenInvalid('Invalid color.')
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    private function getSwiftMailerNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('swiftmailer');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('imap_copy')
                    ->canBeDisabled()
                    ->children()
                        ->scalarNode('mailbox')
                            ->info('Like "{imap.example.org:993/imap/ssl}/INBOX.Sent", see imap_open() function.')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('user')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('password')
                            ->cannotBeEmpty()
                        ->end()
                        ->scalarNode('folder')
                            ->cannotBeEmpty()
                        ->end()
                        ->booleanNode('enabled')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }
}
