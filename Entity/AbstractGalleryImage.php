<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model\ImageGalleryInterface;

/**
 * Class AbstractGalleryImage
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractGalleryImage extends AbstractImage implements ImageGalleryInterface
{
    /**
     * The image position in the gallery
     * 
     * @var integer
     */
    protected $position = 0;

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }
}
