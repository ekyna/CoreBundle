<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

/**
 * Class AbstractGalleryImage
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractGalleryImage extends AbstractImage
{
    /**
     * The image position in the gallery
     * 
     * @var integer
     */
    protected $position = 0;

    /**
     * Returns the image position in the gallery
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set the image position in the gallery
     *
     * @param integer $position
     * @return AbstractGalleryImage|$this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
