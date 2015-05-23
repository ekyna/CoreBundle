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
                ->append($this->getAssetsNode())
                ->append($this->getUiNode())
                ->append($this->getRouterNode())
                ->append($this->getCacheNode())
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
				->scalarNode('enable')->defaultTrue()->end()
				->arrayNode('routers_by_id')
					->useAttributeAsKey('id')
					->prototype('scalar')->end()
                    ->defaultValue(array('router.default' => 100))
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
				->variableNode('tinymce_formats')->defaultNull()->end()
			->end()
		;
		
		return $node;
	}

    private function getAssetsNode()
    {
		$builder = new TreeBuilder();
		$node = $builder->root('assets');
	
		$defaultBootstrapCssInputs = array(
			'@EkynaCoreBundle/Resources/asset/less/bootstrap.less',
			'%kernel.root_dir%/../vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/BootstrapBundle/Resources/less/form.less',
		);
		$defaultJQueryInputs = array(
			'%kernel.root_dir%/../vendor/components/jquery/jquery.js'
		);
		$defaultContentInputs = array(
			'@bootstrap_css',
			'@EkynaCoreBundle/Resources/asset/css/content.css',
		);

		$node
			->addDefaultsIfNotSet()
			->children()
				->scalarNode('output_dir')->defaultValue('')->end()
				->arrayNode('bootstrap_css')
                    ->addDefaultsIfNotSet()
					->children()
						->booleanNode('enabled')->defaultTrue()->end()
						->arrayNode('inputs')
                            ->treatNullLike(array())
                            ->prototype('scalar')->end()
                            ->defaultValue($defaultBootstrapCssInputs)
                        ->end()
					->end()
				->end()
				->arrayNode('bootstrap_js')
                    ->addDefaultsIfNotSet()
					->children()
						->booleanNode('enabled')->defaultTrue()->end()
						->arrayNode('plugins')
                            ->addDefaultsIfNotSet()
							->children()
								->booleanNode('transition')->defaultTrue()->end()
								->booleanNode('alert')->defaultTrue()->end()
								->booleanNode('button')->defaultTrue()->end()
								->booleanNode('carousel')->defaultTrue()->end()
								->booleanNode('collapse')->defaultTrue()->end()
								->booleanNode('dropdown')->defaultTrue()->end()
								->booleanNode('modal')->defaultTrue()->end()
								->booleanNode('tooltip')->defaultTrue()->end()
								->booleanNode('popover')->defaultTrue()->end()
								->booleanNode('scrollspy')->defaultTrue()->end()
								->booleanNode('tab')->defaultTrue()->end()
								->booleanNode('affix')->defaultTrue()->end()
								->booleanNode('collection')->defaultTrue()->end()
							->end()
						->end()
					->end()
				->end()
				->arrayNode('jquery')
                    ->addDefaultsIfNotSet()
					->children()
						->booleanNode('enabled')->defaultTrue()->end()
						->arrayNode('inputs')
                            ->treatNullLike(array())
                            ->prototype('scalar')->end()
                            ->defaultValue($defaultJQueryInputs)
                        ->end()
					->end()
				->end()
				->arrayNode('content_css')
                    ->addDefaultsIfNotSet()
					->children()
						->booleanNode('enabled')->defaultTrue()->end()
						->arrayNode('inputs')
                            ->treatNullLike(array())
                            ->prototype('scalar')->end()
                            ->defaultValue($defaultContentInputs)
                        ->end()
					->end()
				->end()
			->end()
		;

		return $node;
    }
}
