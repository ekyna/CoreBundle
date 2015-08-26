<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Oneup\UploaderBundle\Event\PostUploadEvent;
use Oneup\UploaderBundle\UploadEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class UploadListener
 * @package Ekyna\Bundle\CoreBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UploadListener implements EventSubscriberInterface
{
    /**
     * Post upload event handler (returns the upload key).
     *
     * @param PostUploadEvent $event
     */
    public function onPostUpload(PostUploadEvent $event)
    {
        $response = $event->getResponse();

        $key = null;

        $file = $event->getFile();
        if ($file instanceof File) {
            $key = sprintf('%s://%s', 'local_tmp', $file->getFileName());

            // TODO check if mountManager has key
        }

        $response['upload_key'] = $key;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            UploadEvents::POST_UPLOAD => array('onPostUpload'),
        );
    }
}
