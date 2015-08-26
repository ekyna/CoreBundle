<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;

/**
 * Interface UploaderInterface
 * @package Ekyna\Bundle\CoreBundle\Uploader
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UploaderInterface
{
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

    /**
     * Generates a unique path.
     *
     * @param string $filename
     * @return string
     */
    public function generatePath($filename);
}
