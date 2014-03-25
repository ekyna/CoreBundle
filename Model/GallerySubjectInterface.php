<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * GallerySubjectInterface
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
