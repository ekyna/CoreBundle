<?php

namespace Ekyna\Bundle\CoreBundle;

use Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler as Pass;
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

        $container->addCompilerPass(new Pass\FosHttpCachePass());
        $container->addCompilerPass(new Pass\RedirectionProviderPass());
        $container->addCompilerPass(new Pass\SetRouterPass());
        $container->addCompilerPass(new Pass\RegisterRoutersPass('ekyna_core.router'), PassConfig::TYPE_BEFORE_REMOVING);
        $container->addCompilerPass(new Pass\FormJsPass());
    }
}
