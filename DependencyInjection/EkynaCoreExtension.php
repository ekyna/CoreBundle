<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

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

        $routers = (array) $config['chain_router']['routers_by_id'];
        if (!empty($routers)) {
            $container->setParameter('ekyna_core.chain_router.routers', $routers);
        }

        $container->setParameter('ekyna_core.ui_config', $config['ui']);
        $container->setParameter('ekyna_core.cache_config', $config['cache']);

        /* Inheritance mapping = [
         *     resource_id => [
         *         'class' => Class ,
         *         'repository' => Repository class ,
         *     ]
         * ] */
        if (!$container->hasParameter('ekyna_core.entities')) {
            $container->setParameter('ekyna_core.entities', []);
        }

        /* Target entities resolution
         * [ Interface => Class or class parameter ]
         */
        if (!$container->hasParameter('ekyna_core.interfaces')) {
            $container->setParameter('ekyna_core.interfaces', []);
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

        if (array_key_exists('AsseticBundle', $bundles)) {
            $this->configureAsseticBundle($container, $config['assets']);
        }
        if (array_key_exists('TwigBundle', $bundles)) {
            $this->configureTwigBundle($container);
        }
        if (array_key_exists('BraincraftedBootstrapBundle', $bundles)) {
            $this->configureBraincraftedBootstrapBundle($container);
        }
        if (array_key_exists('KnpMenuBundle', $bundles)) {
            $this->configureKnpMenuBundle($container);
        }
        if (array_key_exists('StfalconTinymceBundle', $bundles)) {
            $this->configureStfalconTinymceBundle($container, $config, $bundles);
        }
        if ($config['cache']['enable'] && array_key_exists('FOSHttpCacheBundle', $bundles)) {
            $this->configureFOSHttpCacheBundle($container);
        }
    }

    /**
     * Configures the TwigBundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureTwigBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('twig', array(
            'form' => array('resources' => array('EkynaCoreBundle:Form:form_div_layout.html.twig')),
        ));
    }

    /**
     * Configures the AsseticBundle.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    protected function configureAsseticBundle(ContainerBuilder $container, array $config)
    {
        $asseticConfig = new AsseticConfiguration();
        $container->prependExtensionConfig('assetic', array(
            'assets' => $asseticConfig->build($config),
            'bundles' => array('EkynaCoreBundle'),
        ));
    }

    /**
     * Configures the BraincraftedBootstrapBundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureBraincraftedBootstrapBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('braincrafted_bootstrap', array(
            'auto_configure' => array(
                'twig' => false,
                'assetic' => false,
                'knp_menu' => false,
            ),
        ));
    }

    /**
     * Configures the KnpMenuBundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureKnpMenuBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('knp_menu', array(
            'twig' => array(
                'template' => 'EkynaCoreBundle:Ui:menu.html.twig',
            ),
        ));
    }

    /**
     * Configures the StfalconTinymceBundle.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     * @param array            $bundles
     */
    protected function configureStfalconTinymceBundle(ContainerBuilder $container, array $config, array $bundles)
    {
        $tinymceConfig = new TinymceConfiguration();
        $container->prependExtensionConfig('stfalcon_tinymce', $tinymceConfig->build($config, $bundles));
    }

    /**
     * Configures the FOSHttpCacheBundle.
     *
     * @param ContainerBuilder $container
     */
    protected function configureFOSHttpCacheBundle(ContainerBuilder $container)
    {
        $container->prependExtensionConfig('fos_http_cache', array(
            'proxy_client' => array(
                'default' =>  'varnish',
                'varnish' => array(
                    'servers' =>  "%reverse_proxy.host%:%reverse_proxy.port%",
                    'base_url' => "%hostname%:%reverse_proxy.port%",
                ),
            ),
        ));
    }
}
