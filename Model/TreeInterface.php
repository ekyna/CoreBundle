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
     * @return TreeInterface|$this
     */
    public function setLeft($left);

    /**
     * Returns the left.
     *
     * @return int
     */
    public function getLeft();

    /**
     * Sets the right.
     *
     * @param int $right
     * @return TreeInterface|$this
     */
    public function setRight($right);

    /**
     * Returns the right.
     *
     * @return int
     */
    public function getRight();

    /**
     * Sets the root.
     *
     * @param int $root
     * @return TreeInterface|$this
     */
    public function setRoot($root);

    /**
     * Returns the root.
     *
     * @return int
     */
    public function getRoot();

    /**
     * Sets the level.
     *
     * @param int $level
     * @return TreeInterface|$this
     */
    public function setLevel($level);

    /**
     * Returns the level.
     *
     * @return int
     */
    public function getLevel();
}