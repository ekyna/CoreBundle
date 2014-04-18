<?php

namespace Ekyna\Bundle\CoreBundle\Listener;

use Ekyna\Bundle\CoreBundle\Uploader\ImageUploader;
use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * ImageEventSubscriber
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImageEventSubscriber implements EventSubscriber
{
    /**
     * @var \Ekyna\Bundle\CoreBundle\Uploader\ImageUploader
     */
    private $uploader;

    /**
     * @param ImageUploader $uploader
     */
    public function __construct(ImageUploader $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if($entity instanceof ImageInterface) {
            $this->prepareImage($entity);
        }
    }

    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if($entity instanceof ImageInterface) {
            $this->uploadImage($entity);
        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if($entity instanceof ImageInterface) {
            $this->prepareImage($entity);
        }
    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if($entity instanceof ImageInterface) {
            $this->uploadImage($entity);
        }
    }

    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if($entity instanceof ImageInterface) {
            $entity->setOldPath($entity->getPath());
        }
    }

    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if($entity instanceof ImageInterface) {
            $this->removeImage($entity);
        }
    }

    private function prepareImage(ImageInterface $image)
    {
        if(!$this->uploader->prepare($image)) {
            //$event->stop('Failed to upload '.$image->getFile()->getFilename().'. Maybe the file allready exists.');
        }
    }

    private function uploadImage(ImageInterface $image)
    {
        if(!$this->uploader->upload($image)) {
            //$event->stop('Failed to upload '.$image->getFile()->getFilename().'. Maybe the file allready exists.');
        }
    }

    private function removeImage(ImageInterface $image)
    {
        if(!$this->uploader->remove($image)) {
            //$event->stop('Failed to remove '.$image->getFile()->getFilename().'. Maybe the file is missing.');
        }
    }

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
