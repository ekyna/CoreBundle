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
                //->append($this->getTinymceNode())
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
                    ->prototype('scalar')->end()
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
                ->scalarNode('google_font_url')->defaultValue('')->end()
                ->variableNode('locales')->defaultValue('%locales%')->end()
                ->booleanNode('tinymce_formats_merge')->defaultTrue()->end()
                ->variableNode('tinymce_formats')->defaultNull()->end()
                ->arrayNode('stylesheets')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('contents')
                            ->treatNullLike([])
                            ->defaultValue(['bundles/ekynacore/css/content.css'])
                            ->prototype('scalar')->cannotBeEmpty()->end()
                        ->end()
                        ->arrayNode('forms')
                            ->treatNullLike([])
                            ->defaultValue([])
                            ->prototype('scalar')->cannotBeEmpty()->end()
                        ->end()
                        ->arrayNode('fonts')
                            ->treatNullLike([])
                            ->defaultValue([])
                            ->prototype('scalar')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    private function getTinymceNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('tinymce');

        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('theme')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->useAttributeAsKey('name')
                        ->prototype('variable')->end()
                    ->end()
                    // Add default theme if it doesn't set
                    ->defaultValue($this->getTinymceDefaultThemes())
                ->end()
                // Configure custom TinyMCE buttons
                ->arrayNode('tinymce_buttons')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('text')->defaultNull()->end()
                            ->scalarNode('title')->defaultNull()->end()
                            ->scalarNode('image')->defaultNull()->end()
                            ->scalarNode('icon')->defaultNull()->end()
                        ->end()
                    ->end()
                ->end()
                // Configure external TinyMCE plugins
                ->arrayNode('external_plugins')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('url')->isRequired()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Get default configuration of the each instance of editor
     *
     * @return array
     */
    private function getTinymceDefaultThemes()
    {
        return array(
            'advanced' => array(
                "theme"        => "modern",
                "plugins"      => array(
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor",
                ),
                "toolbar1"     => "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify " .
                    "| bullist numlist outdent indent | link image",
                "toolbar2"     => "print preview media | forecolor backcolor emoticons",
                "image_advtab" => true,
            ),
            'simple'   => array(),
        );
    }
}
