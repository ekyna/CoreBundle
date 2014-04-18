<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Model\ImageInterface;

/**
 * ImageUploaderInterface
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ImageUploaderInterface
{
    /**
     * Prepare an image for upload
     *
     * @param ImageInterface $image
     */
    public function prepare(ImageInterface $image);

    /**
     * Move image file to image path
     * 
     * @param ImageInterface $image
     */
    public function upload(ImageInterface $image);

    /**
     * Unlink image path file
     *  
     * @param ImageInterface $image
     */
    public function remove(ImageInterface $image);
}
