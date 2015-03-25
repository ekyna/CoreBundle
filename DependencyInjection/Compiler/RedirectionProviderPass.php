<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class RedirectionProviderPass
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RedirectionProviderPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ekyna_core.redirection.provider_registry')) {
            return;
        }

        $definition = $container->getDefinition('ekyna_core.redirection.provider_registry');
        $services = $container->findTaggedServiceIds('ekyna_core.redirection_provider');

        foreach ($services as $service => $attributes) {
            $definition->addMethodCall('addProvider', array(new Reference($service)));
        }
    }
}
