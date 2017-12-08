<?php

namespace Ekyna\Bundle\CoreBundle\Listener;

use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
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
     * @param UploadableInterface $upload
     */
    public function prePersist(UploadableInterface $upload)
    {
        $this->uploader->prepare($upload);
    }

    /**
     * Post persist event handler.
     *
     * @param UploadableInterface $upload
     */
    public function postPersist(UploadableInterface $upload)
    {
        $this->uploader->upload($upload);
    }

    /**
     * Pre update event handler.
     *
     * @param UploadableInterface $upload
     */
    public function preUpdate(UploadableInterface $upload)
    {
        $this->uploader->prepare($upload);
    }

    /**
     * Post update event handler.
     *
     * @param UploadableInterface $upload
     */
    public function postUpdate(UploadableInterface $upload)
    {
        $this->uploader->upload($upload);
    }

    /**
     * Pre remove event handler.
     *
     * @param UploadableInterface $upload
     */
    public function preRemove(UploadableInterface $upload)
    {
        $upload->setOldPath($upload->getPath());
    }

    /**
     * Post remove event handler.
     *
     * @param UploadableInterface $upload
     */
    public function postRemove(UploadableInterface $upload)
    {
        $this->uploader->remove($upload);
    }
}
