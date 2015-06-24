<?php

namespace Ekyna\Bundle\CoreBundle\Listener;

use Ekyna\Bundle\CoreBundle\Entity\Upload;
use Ekyna\Bundle\CoreBundle\Uploader\UploaderInterface;

/**
 * Class UploadListener
 * @package Ekyna\Bundle\CoreBundle\Listener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UploadListener
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
     * @param Upload             $upload
     */
    public function prePersist(Upload $upload)
    {
        $this->uploader->prepare($upload);
    }

    /**
     * Post persist event handler.
     *
     * @param Upload $upload
     */
    public function postPersist(Upload $upload)
    {
        $this->uploader->upload($upload);
    }

    /**
     * Pre update event handler.
     *
     * @param Upload $upload
     */
    public function preUpdate(Upload $upload)
    {
        $this->uploader->prepare($upload);
    }

    /**
     * Post update event handler.
     *
     * @param Upload $upload
     */
    public function postUpdate(Upload $upload)
    {
        $this->uploader->upload($upload);
    }

    /**
     * Pre remove event handler.
     *
     * @param Upload $upload
     */
    public function preRemove(Upload $upload)
    {
        $upload->setOldPath($upload->getPath());
    }

    /**
     * Post remove event handler.
     *
     * @param Upload $upload
     */
    public function postRemove(Upload $upload)
    {
        $this->uploader->remove($upload);
    }
}
