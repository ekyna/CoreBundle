<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * GallerySubjectInterface
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface GallerySubjectInterface
{
    /**
     * Get all images
     * 
     * @return Doctrine\Common\Collections\ArrayCollection
     */
    public function getImages();
}
