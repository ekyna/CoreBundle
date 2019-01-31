<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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

        $this->addCacheSection($rootNode);
        $this->addModalSection($rootNode);
        $this->addRouterSection($rootNode);
        $this->addSwiftMailerSection($rootNode);
        $this->addUiSection($rootNode);

        return $treeBuilder;
    }

    /**
     * Adds the `cache` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addCacheSection(ArrayNodeDefinition $node)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $node
            ->children()
                ->arrayNode('cache')
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
                ->end()
            ->end();
    }

    /**
     * Adds the `modal` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addModalSection(ArrayNodeDefinition $node)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $node
            ->children()
                ->arrayNode('modal')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('template')
                            ->cannotBeEmpty()
                            ->defaultValue('@EkynaCore/Modal/modal.xml.twig')
                        ->end()
                        ->scalarNode('charset')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.charset%')
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds the `router` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addRouterSection(ArrayNodeDefinition $node)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $node
            ->children()
                ->arrayNode('chain_router')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('routers_by_id')
                            ->useAttributeAsKey('id')
                            ->scalarPrototype()->end()
                            ->defaultValue(['router.default' => 1024])
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds the `swiftmailer` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addSwiftMailerSection(ArrayNodeDefinition $node)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $node
            ->children()
                ->arrayNode('swiftmailer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('imap_copy')
                            ->canBeDisabled()
                            ->children()
                                ->scalarNode('mailbox')
                                    ->info('Like "{imap.example.org:993/imap/ssl}", see imap_open() function.')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('folder')
                                    ->info('Like "/INBOX.Sent", see imap_open() function.')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('user')
                                    ->cannotBeEmpty()
                                ->end()
                                ->scalarNode('password')
                                    ->cannotBeEmpty()
                                ->end()
                                ->booleanNode('enabled')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * Adds the `ui` section.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addUiSection(ArrayNodeDefinition $node)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $node
            ->children()
                ->arrayNode('ui')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('controls_template')->defaultValue('@EkynaCore/Ui/controls.html.twig')->end()
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
                                    ->defaultValue([])
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
                ->end()
            ->end();
    }
}
