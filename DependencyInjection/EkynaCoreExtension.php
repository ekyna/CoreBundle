<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * Class EkynaCoreExtension
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaCoreExtension extends Extension implements PrependExtensionInterface
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

        if ($config['chain_router']['enable']) {
            $container->setParameter('ekyna_core.enable_chain_router', true);

            // add the routers defined in the configuration mapping
            $router = $container->getDefinition($this->getAlias() . '.router');
            foreach ($config['chain_router']['routers_by_id'] as $id => $priority) {
                $router->addMethodCall('add', array(new Reference($id), $priority));
            }
        }

        $container->setParameter('ekyna_core.ui_config', $config['ui']);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
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
            $this->configureStfalconTinymceBundle($container, $config);
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
     */
    protected function configureStfalconTinymceBundle(ContainerBuilder $container, array $config)
    {
        $tinymceConfig = new TinymceConfiguration();
        $container->prependExtensionConfig('stfalcon_tinymce', $tinymceConfig->build($config));
    }
}
