<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Trait TreeTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait TreeTrait
{
    /**
     * @var integer
     */
    protected $left;

    /**
     * @var integer
     */
    protected $right;

    /**
     * @var integer
     */
    protected $root;

    /**
     * @var integer
     */
    protected $level;

    /**
     * Sets the left.
     *
     * @param int $left
     * @return TreeInterface|$this
     */
    public function setLeft($left)
    {
        $this->left = $left;
        return $this;
    }

    /**
     * Returns the left.
     *
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * Sets the right.
     *
     * @param int $right
     * @return TreeInterface|$this
     */
    public function setRight($right)
    {
        $this->right = $right;
        return $this;
    }

    /**
     * Returns the right.
     *
     * @return int
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * Sets the root.
     *
     * @param int $root
     * @return TreeInterface|$this
     */
    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
    }

    /**
     * Returns the root.
     *
     * @return int
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * Sets the level.
     *
     * @param int $level
     * @return TreeInterface|$this
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Returns the level.
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }
}
