<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
use Gaufrette\Filesystem;

/**
 * Interface UploaderInterface
 * @package Ekyna\Bundle\CoreBundle\Uploader
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UploaderInterface
{
    /**
     * Sets the target filesystem.
     *
     * @param Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem);

    /**
     * Prepare the entity for upload.
     *
     * @param UploadableInterface $image
     */
    public function prepare(UploadableInterface $image);

    /**
     * Move the uploadable file.
     * 
     * @param UploadableInterface $image
     */
    public function upload(UploadableInterface $image);

    /**
     * Unlink the file.
     *  
     * @param UploadableInterface $image
     */
    public function remove(UploadableInterface $image);
}
