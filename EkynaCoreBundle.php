<?php

namespace Ekyna\Bundle\CoreBundle;

use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Cmf\Component\Routing\DependencyInjection\Compiler\RegisterRoutersPass;
//use Symfony\Cmf\Component\Routing\DependencyInjection\Compiler\RegisterRouteEnhancersPass;

class EkynaCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DoctrineEntityListenerPass());
        $container->addCompilerPass(new RegisterRoutersPass('ekyna_core.router'));
        //$container->addCompilerPass(new RegisterRouteEnhancersPass());
    }
}
