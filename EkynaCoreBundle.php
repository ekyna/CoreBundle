<?php

namespace Ekyna\Bundle\CoreBundle;

use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;
use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\FormJsPass;
use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\FosHttpCachePass;
use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\RedirectionProviderPass;
use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\RegisterRoutersPass;
use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler\SetRouterPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EkynaCoreBundle
 * @package Ekyna\Bundle\CoreBundle
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaCoreBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineEntityListenerPass());
        $container->addCompilerPass(new FosHttpCachePass());
        $container->addCompilerPass(new RedirectionProviderPass());
        $container->addCompilerPass(new SetRouterPass());
        $container->addCompilerPass(new RegisterRoutersPass('ekyna_core.router'), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new FormJsPass());
    }
}
