<?php

namespace Ekyna\Bundle\CoreBundle;

use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;
use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\FosHttpCachePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Cmf\Component\Routing\DependencyInjection\Compiler\RegisterRoutersPass;
use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\SetRouterPass;

/**
 * Class EkynaCoreBundle
 * @package Ekyna\Bundle\CoreBundle
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaCoreBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new DoctrineEntityListenerPass());
        $container->addCompilerPass(new FosHttpCachePass());
        $container->addCompilerPass(new SetRouterPass());
        $container->addCompilerPass(new RegisterRoutersPass('ekyna_core.router'));
    }
}
