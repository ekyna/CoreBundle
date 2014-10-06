<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * EkynaCoreExtension
 *
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
            $this->configureAsseticBundle($container, $config);
        }
        if (array_key_exists('TwigBundle', $bundles)) {
            $this->configureTwigBundle($container);
        }
        if (array_key_exists('StfalconTinymceBundle', $bundles)) {
            $this->configureTinymceBundle($container, $config);
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
     * Configures the TinymceBundle.
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    protected function configureTinymceBundle(ContainerBuilder $container, array $config)
    {
        $asseticConfig = new TinymceConfiguration();
        $container->prependExtensionConfig('stfalcon_tinymce', $asseticConfig->build($config));
    }
}
