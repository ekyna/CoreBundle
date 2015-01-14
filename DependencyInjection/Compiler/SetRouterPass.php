<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class SetRouterPass
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 * @see https://github.com/symfony-cmf/RoutingBundle/blob/master/DependencyInjection/Compiler/SetRouterPass.php
 */
class SetRouterPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('ekyna_core.enable_chain_router') && true === $container->getParameter('ekyna_core.enable_chain_router')) {
            $container->setAlias('router', 'ekyna_core.router');
        }
    }
}