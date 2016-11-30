<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class FosHttpCachePass
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FosHttpCachePass implements CompilerPassInterface
{
    const FOS_HTTP_TAG_HANDLER_ID = 'fos_http_cache.handler.tag_handler';

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition(self::FOS_HTTP_TAG_HANDLER_ID)) {
            $tagManagerDefinition = $container->getDefinition('ekyna_core.cache.tag_manager');

            $tagManagerDefinition->addMethodCall(
                'setTagHandler',
                [new Reference(self::FOS_HTTP_TAG_HANDLER_ID)]
            );
        }
    }
}
