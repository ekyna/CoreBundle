<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Trait SoftDeleteableTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait SoftDeleteableTrait
{
    /**
     * @var \DateTime
     */
    protected $deletedAt;

    /**
     * Sets the deletedAt.
     *
     * @param \DateTime $deletedAt
     * @return SoftDeleteableTrait
     */
    public function setDeletedAt(\DateTime $deletedAt = null)
    {
        $this->deletedAt = $deletedAt;
        return $this;
    }

    /**
     * Returns the deletedAt.
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }
}
