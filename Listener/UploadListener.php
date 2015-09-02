<?php

namespace Ekyna\Bundle\CoreBundle\Listener;

use Ekyna\Bundle\CoreBundle\Entity\AbstractUpload;
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
     * @param AbstractUpload $upload
     */
    public function prePersist(AbstractUpload $upload)
    {
        $this->uploader->prepare($upload);
    }

    /**
     * Post persist event handler.
     *
     * @param AbstractUpload $upload
     */
    public function postPersist(AbstractUpload $upload)
    {
        $this->uploader->upload($upload);
    }

    /**
     * Pre update event handler.
     *
     * @param AbstractUpload $upload
     */
    public function preUpdate(AbstractUpload $upload)
    {
        $this->uploader->prepare($upload);
    }

    /**
     * Post update event handler.
     *
     * @param AbstractUpload $upload
     */
    public function postUpdate(AbstractUpload $upload)
    {
        $this->uploader->upload($upload);
    }

    /**
     * Pre remove event handler.
     *
     * @param AbstractUpload $upload
     */
    public function preRemove(AbstractUpload $upload)
    {
        $upload->setOldPath($upload->getPath());
    }

    /**
     * Post remove event handler.
     *
     * @param AbstractUpload $upload
     */
    public function postRemove(AbstractUpload $upload)
    {
        $this->uploader->remove($upload);
    }
}
