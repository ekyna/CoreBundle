<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Trait TaggedEntityTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 *
 * @method int getId()
 */
trait TaggedEntityTrait
{
    /**
     * Returns the entity tag.
     *
     * @throws \RuntimeException
     * @return string
     */
    public function getEntityTag()
    {
        if (null === $this->getId()) {
            throw new \RuntimeException('Unable to generate entity tag, as the id property is undefined.');
        }
        return sprintf('%s[id:%s]', static::getEntityTagPrefix(), $this->getId());
    }

    /**
     * Returns the entity and his related entities tags.
     *
     * @return array
     */
    public function getEntityTags()
    {
        return array($this->getEntityTag());
    }

    /**
     * Returns the entity tag.
     *
     * @return string
     */
    public static function getEntityTagPrefix()
    {
        throw new \BadMethodCallException('Must be implemented');
    }
}
