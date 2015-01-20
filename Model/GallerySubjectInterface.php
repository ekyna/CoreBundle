<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface GallerySubjectInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 * @deprecated (moved to CmsBundle)
 */
interface GallerySubjectInterface
{
    /**
     * Get all images
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getImages();
}
