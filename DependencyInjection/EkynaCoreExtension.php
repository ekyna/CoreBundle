<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class EkynaCoreExtension
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaCoreExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        // Routers
        /* @see \Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterRoutersPass */
        $routers = (array) $config['chain_router']['routers_by_id'];
        if (!empty($routers)) {
            $container->setParameter('ekyna_core.chain_router.routers', $routers);
        }

        // Tinymce
        $bundles = $container->getParameter('kernel.bundles');
        $tinymceCfgBuilder = new TinymceConfigBuilder($container->getParameter('kernel.environment') == 'dev');
        $tinymceConfig = $tinymceCfgBuilder->build($config, $bundles) ;

        $container->setParameter('ekyna_core.config.tinymce', $tinymceConfig);
        $container->setParameter('ekyna_core.config.tinymce_themes', array_keys($tinymceConfig['theme']));

        // UI
        if (!in_array('bundles/ekynacore/css/form.css', $config['ui']['stylesheets']['forms'])) {
            $config['ui']['stylesheets']['forms'][] = 'bundles/ekynacore/css/form.css';
        }
        $container
            ->getDefinition('ekyna_core.ui.renderer')
            ->replaceArgument(1, $config['ui']);
        $container
            ->getDefinition('ekyna_core.color_picker.form_type')
            ->replaceArgument(0, $config['ui']['colors']);

        // Modal
        $container
            ->getDefinition('ekyna_core.modal')
            ->replaceArgument(3, $config['modal']);

        // Swiftmailer imag copy
        if (!empty($imapCopy = $config['swiftmailer']['imap_copy'])) {
            $container
                ->getDefinition('ekyna_core.swiftmailer.imap_copy_plugin')
                ->setArgument(1, $imapCopy)
                ->addTag('swiftmailer.default.plugin')
                ->addTag('kernel.event_subscriber');
        }

        // Http cache
        $container
            ->getDefinition('ekyna_core.cache.tag_manager')
            ->replaceArgument(0, $config['cache']);

        if ($config['cache']['enable'] && $container->hasDefinition('fos_http_cache.handler.tag_handler')) {
            $container
                ->getDefinition('ekyna_core.cache.tag_manager')
                ->addMethodCall(
                    'setTagHandler',
                    [new Reference('fos_http_cache.handler.tag_handler', ContainerInterface::NULL_ON_INVALID_REFERENCE)]
                );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        parent::prepend($container);

        $bundles = $container->getParameter('kernel.bundles');
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        if ($config['cache']['enable'] && array_key_exists('FOSHttpCacheBundle', $bundles)) {
            $container->prependExtensionConfig('fos_http_cache', [
                'proxy_client' => [
                    'default' =>  'varnish',
                    'varnish' => [
                        'servers' =>  "%reverse_proxy.host%:%reverse_proxy.port%",
                        'base_url' => "%hostname%:%reverse_proxy.port%",
                    ],
                ],
            ]);
        }
    }
}
