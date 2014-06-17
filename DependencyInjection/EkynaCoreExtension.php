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
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $configs = $container->getExtensionConfig($this->getAlias());
        $config = $this->processConfiguration(new Configuration(), $configs);

        if (true === isset($bundles['AsseticBundle'])) {
            $this->configureAsseticBundle($container, $config);
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param array            $config
     *
     * @return void
     */
    protected function configureAsseticBundle(ContainerBuilder $container, array $config)
    {
        foreach (array_keys($container->getExtensions()) as $name) {
            if ($name == 'assetic') {
                $asseticConfig = new AsseticConfiguration;
                $container->prependExtensionConfig(
                    $name,
                    array(
                        'assets' => $asseticConfig->build($config),
                    )
                );
                break;
            }
        }
    }
}
