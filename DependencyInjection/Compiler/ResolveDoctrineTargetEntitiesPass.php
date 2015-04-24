<?php

namespace Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class ResolveDoctrineTargetEntitiesPass
 * @package Ekyna\Bundle\CoreBundle\DependencyInjection\Compiler
 * @author Paweł Jędrzejewski <pjedrzejewski@sylius.pl>
 * @see https://github.com/Sylius/SyliusResourceBundle/blob/master/DependencyInjection/DoctrineTargetEntitiesResolver.php
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ResolveDoctrineTargetEntitiesPass implements CompilerPassInterface
{
    /**
     * @var array $interfaces
     */
    private $interfaces;


    /**
     * Constructor.
     *
     * @param array $interfaces
     */
    public function __construct(array $interfaces)
    {
        $this->interfaces = $interfaces;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('doctrine.orm.listeners.resolve_target_entity')) {
            throw new \RuntimeException('Cannot find Doctrine RTEL');
        }

        $resolvedInterfaces = [];
        $resolveTargetEntityListener = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        foreach ($this->interfaces as $interface => $model) {
            $i = $this->getInterface($container, $interface);
            $c = $this->getClass($container, $model);

            $resolveTargetEntityListener
                ->addMethodCall('addResolveTargetEntity', array($i, $c, array()))
            ;

            $resolvedInterfaces[$i] = $c;
        }

        if ($container->hasParameter('ekyna_core.interfaces')) {
            $resolvedInterfaces = array_merge($container->getParameter('ekyna_core.interfaces'), $resolvedInterfaces);
        }
        $container->setParameter('ekyna_core.interfaces', $resolvedInterfaces);

        if (!$resolveTargetEntityListener->hasTag('doctrine.event_listener')) {
            $resolveTargetEntityListener->addTag('doctrine.event_listener', array('event' => 'loadClassMetadata'));
        }
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $key
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getInterface(ContainerBuilder $container, $key)
    {
        if ($container->hasParameter($key)) {
            return $container->getParameter($key);
        }

        if (interface_exists($key)) {
            return $key;
        }

        throw new \InvalidArgumentException(
            sprintf('The interface %s does not exist.', $key)
        );
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $key
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    private function getClass(ContainerBuilder $container, $key)
    {
        if ($container->hasParameter($key)) {
            return $container->getParameter($key);
        }

        if (class_exists($key)) {
            return $key;
        }

        throw new \InvalidArgumentException(
            sprintf('The class %s does not exist.', $key)
        );
    }
}
