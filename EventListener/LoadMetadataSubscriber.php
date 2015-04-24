<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * Class LoadMetadataSubscriber
 * @package Ekyna\Bundle\CoreBundle\EventListener
 * @author Ivan Molchanov <ivan.molchanov@opensoftdev.ru>
 * @see https://github.com/Sylius/SyliusResourceBundle/blob/master/EventListener/LoadORMMetadataSubscriber.php
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class LoadMetadataSubscriber implements EventSubscriber
{
    /**
     * @var array
     */
    protected $entities;

    /**
     * @var array
     */
    protected $interfaces;


    /**
     * Constructor
     *
     * @param array $entities
     * @param array $interfaces
     */
    public function __construct(array $entities, array $interfaces)
    {
        $this->entities   = $entities;
        $this->interfaces = $interfaces;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(Events::loadClassMetadata);
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $this->setCustomRepositoryClasses($metadata);
        if (!$metadata->isMappedSuperclass) {
            $this->setAssociationMappings($metadata, $eventArgs->getEntityManager()->getConfiguration());
        } else {
            $this->unsetAssociationMappings($metadata);
        }
    }

    private function setCustomRepositoryClasses(ClassMetadataInfo $metadata)
    {
        foreach ($this->entities as $entity) {
            if (array_key_exists('class', $entity) && $entity['class'] === $metadata->getName()) {
                $metadata->isMappedSuperclass = false;
                if (array_key_exists('repository', $entity)) {
                    $metadata->setCustomRepositoryClass($entity['repository']);
                }
                return;
            }
        }
        if (in_array($metadata->getName(), $this->interfaces)) {
            $metadata->isMappedSuperclass = false;
        }
    }

    private function setAssociationMappings(ClassMetadataInfo $metadata, $configuration)
    {
        /** @var \Doctrine\ORM\Configuration $configuration */
        foreach (class_parents($metadata->getName()) as $parent) {
            $parentMetadata = new ClassMetadata(
                $parent,
                $configuration->getNamingStrategy()
            );
            if (in_array($parent, $configuration->getMetadataDriverImpl()->getAllClassNames())) {
                $configuration->getMetadataDriverImpl()->loadMetadataForClass($parent, $parentMetadata);
                if ($parentMetadata->isMappedSuperclass) {
                    foreach ($parentMetadata->getAssociationMappings() as $key => $value) {
                        if ($this->hasRelation($value['type'])) {
                            $metadata->associationMappings[$key] = $value;
                        }
                    }
                }
            }
        }
    }

    private function unsetAssociationMappings(ClassMetadataInfo $metadata)
    {
        foreach ($metadata->getAssociationMappings() as $key => $value) {
            if ($this->hasRelation($value['type'])) {
                unset($metadata->associationMappings[$key]);
            }
        }
    }

    private function hasRelation($type)
    {
        return in_array(
            $type,
            array(
                ClassMetadataInfo::MANY_TO_MANY,
                ClassMetadataInfo::ONE_TO_MANY,
                ClassMetadataInfo::ONE_TO_ONE,
            ),
            true
        );
    }
}
