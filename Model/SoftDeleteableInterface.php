<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface SoftDeleteableInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface SoftDeleteableInterface
{
    /**
     * Sets the deletedAt.
     *
     * @param \DateTime $deletedAt
     * @return SoftDeleteableTrait
     */
    public function setDeletedAt(\DateTime $deletedAt = null);

    /**
     * Returns the deletedAt.
     *
     * @return \DateTime
     */
    public function getDeletedAt();
}
