<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface TaggedEntityInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface TaggedEntityInterface
{
    /**
     * Returns the entity tag.
     *
     * @throws \RuntimeException
     * @return string
     */
    public function getEntityTag();

    /**
     * Returns the entity and his related entities tags.
     *
     * @return array
     */
    public function getEntityTags();

    /**
     * Returns the entity tag.
     *
     * @return string
     */
    public static function getEntityTagPrefix();
}
