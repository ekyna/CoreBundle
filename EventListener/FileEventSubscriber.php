<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Event\FileEvent;
use Ekyna\Bundle\CoreBundle\Event\FileEvents;
use Ekyna\Bundle\CoreBundle\Uploader\UploaderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class FileEventSubscriber
 * @package Ekyna\Bundle\CoreBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FileEventSubscriber implements EventSubscriberInterface
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
     * File upload event handler.
     * 
     * @param FileEvent $event
     */
    public function onFileUpload(FileEvent $event)
    {
        $file = $event->getFile();
        
        $this->uploader->prepare($file);
        $this->uploader->upload($file);
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FileEvents::UPLOAD => array('onFileUpload', 0),
        );
    }
}
