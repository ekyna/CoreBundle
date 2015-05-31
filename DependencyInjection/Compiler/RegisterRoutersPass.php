<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class RegisterRoutersPass
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 * @see Symfony\Cmf\Component\Routing\DependencyInjection\Compiler\RegisterRoutersPass
 */
class RegisterRoutersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ekyna_core.router')) {
            return;
        }

        $definition = $container->getDefinition('ekyna_core.router');

        // Gather routers
        $routers = array();
        if ($container->hasParameter('ekyna_core.chain_router.routers')) {
            $routers = $container->getParameter('ekyna_core.chain_router.routers');
        }
        foreach ($container->findTaggedServiceIds('router') as $id => $attributes) {
            $priority = isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 0;
            $routers[$id] = $priority;
        }

        // Register routers
        foreach ($routers as $id => $priority) {
            $definition->addMethodCall('add', array(new Reference($id), $priority));
        }
    }
}
