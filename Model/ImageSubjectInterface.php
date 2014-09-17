<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface ImageSubjectInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ImageSubjectInterface
{
    /**
     * Get image
     * 
     * @return ImageInterface
     */
    public function getImage();
}
