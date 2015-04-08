<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Oneup\UploaderBundle\UploadEvents;
use Oneup\UploaderBundle\Event\PostUploadEvent;
use Symfony\Component\HttpFoundation\File\File as SFile;
use Gaufrette\File as GFile;

/**
 * Class UploadListener
 * @package Ekyna\Bundle\CoreBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UploadListener implements EventSubscriberInterface
{
    public function onPostUpload(PostUploadEvent $event)
    {
        $response = $event->getResponse();

        $key = null;
        $file = $event->getFile();

        if ($file instanceof SFile) {
            $key = $file->getFileName();
        } elseif($file instanceof GFile) {
            $key = $file->getKey();
        }

        $response['upload_key'] = $key;
    }

    public static function getSubscribedEvents()
    {
        return array(
            UploadEvents::POST_UPLOAD => array('onPostUpload'),
        );
    }
}
