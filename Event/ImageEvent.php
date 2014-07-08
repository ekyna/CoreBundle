<?php

namespace Ekyna\Bundle\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Ekyna\Bundle\CoreBundle\Model\ImageInterface;

/**
 * ImageEvent.
 *
 * @author Etienne Dauvergne <contact@ekyna.com>
 */
class ImageEvent extends Event
{
    /**
     * @var ImageInterface
     */
    protected $image;

    /**
     * Constructor.
     * 
     * @param ImageInterface $image
     */
    public function __construct(ImageInterface $image)
    {
        $this->image = $image;
    }

    /**
     * Returns the image.
     * 
     * @return ImageInterface
     */
    public function getImage()
    {
        return $this->image;
    }
}
