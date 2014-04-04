<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * ImageSubjectInterface
 *
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
