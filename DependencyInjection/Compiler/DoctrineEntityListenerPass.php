<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DoctrineEntityListenerPass
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DoctrineEntityListenerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $ems = $container->getParameter('doctrine.entity_managers');
        foreach ($ems as $name => $em) {
            $container->getDefinition(sprintf('doctrine.orm.%s_configuration', $name))
                ->addMethodCall('setEntityListenerResolver', [new Reference('ekyna_core.doctrine.entity_listener_resolver')])
            ;
        }

        $definition = $container->getDefinition('ekyna_core.doctrine.entity_listener_resolver');
        $services = $container->findTaggedServiceIds('doctrine.entity_listener');

        foreach ($services as $service => $attributes) {
            $definition->addMethodCall(
                'addMapping',
                [$container->getDefinition($service)->getClass(), $service]
            );
        }
    }
}