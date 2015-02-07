<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface ImageInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ImageInterface extends UploadableInterface
{
    /**
     * Get image alt.
     * 
     * @return string
     */
    public function getAlt();

    /**
     * Get image last update datetime.
     * 
     * @return \DateTime
     */
    public function getUpdatedAt();
}
