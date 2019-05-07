<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class FormJsPass
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FormJsPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        // TODO configuration / extension parameter
        $formJs = ['.file-picker' => ['ekyna-form/file-picker']];
        $taggedFormServices = $container->findTaggedServiceIds('form.js');

        foreach ($taggedFormServices as $service => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                if (!array_key_exists('selector', $attributes)) {
                    throw new \InvalidArgumentException(
                        sprintf('The "selector" attributes is missing for tag "form.js" of service "%s"', $service)
                    );
                }
                if (!array_key_exists('path', $attributes)) {
                    throw new \InvalidArgumentException(
                        sprintf('The "path" attributes is missing for tag "form.js" of service "%s"', $service)
                    );
                }

                if (!isset($formJs[$attributes['selector']])) {
                    $formJs[$attributes['selector']] = [];
                }

                $formJs[$attributes['selector']][] = $attributes['path'];
            }
        }

        foreach ($formJs as &$paths) {
            $paths = array_unique($paths);
        }

        $container->setParameter('ekyna_core.form_js', $formJs);
    }
}
