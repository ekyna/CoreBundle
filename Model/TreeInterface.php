<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface TreeInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface TreeInterface
{
    /**
     * Sets the left.
     *
     * @param int $left
     *
     * @return TreeInterface|$this
     */
    public function setLeft(int $left): TreeInterface;

    /**
     * Returns the left.
     *
     * @return int
     */
    public function getLeft(): ?int;

    /**
     * Sets the right.
     *
     * @param int $right
     *
     * @return TreeInterface|$this
     */
    public function setRight(int $right): TreeInterface;

    /**
     * Returns the right.
     *
     * @return int
     */
    public function getRight(): ?int;

    /**
     * Sets the root.
     *
     * @param int $root
     *
     * @return TreeInterface|$this
     */
    public function setRoot(int $root): TreeInterface;

    /**
     * Returns the root.
     *
     * @return int
     */
    public function getRoot(): ?int;

    /**
     * Sets the level.
     *
     * @param int $level
     *
     * @return TreeInterface|$this
     */
    public function setLevel(int $level): TreeInterface;

    /**
     * Returns the level.
     *
     * @return int
     */
    public function getLevel(): ?int;
}
