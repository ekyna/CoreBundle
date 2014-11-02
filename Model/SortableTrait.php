<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Trait SortableTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait SortableTrait
{
    /**
     * @var integer
     */
    protected $position;

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }
}
