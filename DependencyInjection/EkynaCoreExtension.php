<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
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

        // only replace the default router by overwriting the 'router' alias if config tells us to
        if ($config['chain_router']['enable']) {
            // TODO: Define chain router here instead of in services.xml

            $container->setAlias('router', $this->getAlias() . '.router');

            // add the routers defined in the configuration mapping
            $router = $container->getDefinition($this->getAlias() . '.router');
            foreach ($config['chain_router']['routers_by_id'] as $id => $priority) {
                $router->addMethodCall('add', array(new Reference($id), $priority));
            }
        }
    }
}
