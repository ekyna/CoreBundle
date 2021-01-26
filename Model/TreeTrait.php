<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Trait TreeTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
trait TreeTrait
{
    /**
     * @var int
     */
    protected $left;

    /**
     * @var int
     */
    protected $right;

    /**
     * @var int
     */
    protected $root;

    /**
     * @var int
     */
    protected $level;


    /**
     * Sets the left.
     *
     * @param int $left
     *
     * @return TreeInterface|$this
     */
    public function setLeft(int $left): TreeInterface
    {
        $this->left = $left;

        return $this;
    }

    /**
     * Returns the left.
     *
     * @return int
     */
    public function getLeft(): ?int
    {
        return $this->left;
    }

    /**
     * Sets the right.
     *
     * @param int $right
     *
     * @return TreeInterface|$this
     */
    public function setRight(int $right): TreeInterface
    {
        $this->right = $right;

        return $this;
    }

    /**
     * Returns the right.
     *
     * @return int
     */
    public function getRight(): ?int
    {
        return $this->right;
    }

    /**
     * Sets the root.
     *
     * @param int $root
     *
     * @return TreeInterface|$this
     */
    public function setRoot(int $root): TreeInterface
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Returns the root.
     *
     * @return int
     */
    public function getRoot(): ?int
    {
        return $this->root;
    }

    /**
     * Sets the level.
     *
     * @param int $level
     *
     * @return TreeInterface|$this
     */
    public function setLevel(int $level): TreeInterface
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Returns the level.
     *
     * @return int
     */
    public function getLevel(): ?int
    {
        return $this->level;
    }
}
