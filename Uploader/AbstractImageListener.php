<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Ekyna\Bundle\CoreBundle\Uploader\ImageUploaderInterface;

abstract class AbstractImageListener
{
    protected $uploader;

    public function __construct(ImageUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function prePersist(ImageInterface $image, LifecycleEventArgs $eventArgs)
    {
        $this->uploadImage($image);
    }

    public function preUpdate(ImageInterface $image, PreUpdateEventArgs $eventArgs)
    {
        $this->uploadImage($image);
    }

    public function postRemove(ImageInterface $image, LifecycleEventArgs $eventArgs)
    {
        $this->removeImage($image);
    }

    protected function uploadImage(ImageInterface $image)
    {
        if(!$this->uploader->upload($image)) {
            //$event->stop('Failed to upload '.$image->getFile()->getFilename().'. Maybe the file allready exists.');
        }
    }

    protected function removeImage(ImageInterface $image)
    {
        if(!$this->uploader->remove($image)) {
            //$event->stop('Failed to remove '.$image->getFile()->getFilename().'. Maybe the file is missing.');
        }
    }
}
