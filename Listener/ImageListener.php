<?php

namespace Ekyna\Bundle\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Ekyna\Bundle\CoreBundle\Uploader\UploaderInterface;

/**
 * Class ImageListener
 * @package Ekyna\Bundle\CoreBundle\Listener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImageListener implements EventSubscriber
{
    /**
     * @var UploaderInterface
     */
    private $uploader;


    /**
     * @param UploaderInterface $uploader
     */
    public function __construct(UploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    /**
     * Pre persist event handler.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof ImageInterface) {
            $this->uploader->prepare($entity);
        }
    }

    /**
     * Post persist event handler.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof ImageInterface) {
            $this->uploader->upload($entity);
        }
    }

    /**
     * Pre update event handler.
     *
     * @param PreUpdateEventArgs $eventArgs
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof ImageInterface) {
            $this->uploader->prepare($entity);
        }
    }

    /**
     * Post update event handler.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof ImageInterface) {
            $this->uploader->upload($entity);
        }
    }

    /**
     * Pre remove event handler.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof ImageInterface) {
            $entity->setOldPath($entity->getPath());
        }
    }

    /**
     * Post remove event handler.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof ImageInterface) {
            $this->uploader->remove($entity);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::postPersist,
            Events::preUpdate,
            Events::postUpdate,
            Events::preRemove,
            Events::postRemove,
        );
    }
}
