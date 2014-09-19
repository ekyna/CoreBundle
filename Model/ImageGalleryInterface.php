<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface ImageGalleryInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ImageGalleryInterface extends ImageInterface
{
    /**
     * Returns the image position in the gallery
     *
     * @return integer
     */
    public function getPosition();

    /**
     * Set the image position in the gallery
     *
     * @param integer $position
     * @return ImageGalleryInterface|$this
     */
    public function setPosition($position);
}
