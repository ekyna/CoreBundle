<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model\GalleryImageInterface;
use Ekyna\Bundle\CoreBundle\Model\SortableTrait;

/**
 * Class AbstractGalleryImage
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractGalleryImage extends AbstractImage implements GalleryImageInterface
{
    use SortableTrait;
}
