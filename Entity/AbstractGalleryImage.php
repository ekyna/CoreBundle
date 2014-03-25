<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Entity\AbstractImage;

/**
 * AbstractGalleryImage
 */
abstract class AbstractGalleryImage extends AbstractImage
{
    /**
     * The image position in the gallery
     * 
     * @var integer
     */
    protected $position;

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
     * @param interger $position
     * @return \Ekyna\Bundle\CoreBundle\Entity\AbstractGalleryImage
     */
    public function setPosition($position)
    {
        $this->position = $position;
        
        return $this;
    }
}
