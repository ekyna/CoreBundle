<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Trait TaggedEntityTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait TaggedEntityTrait
{
    /**
     * {@inheritdoc}
     */
    public function getEntityTag()
    {
        if (null === $this->getId()) {
            throw new \RuntimeException('Unable to generate entity tag, as the id property is undefined.');
        }
        return sprintf('%s[id:%s]', static::getEntityTagPrefix(), $this->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityTags()
    {
        return array($this->getEntityTag());
    }

    /**
     * @return mixed
     */
    public static function getEntityTagPrefix()
    {
        throw new \BadMethodCallException('Must be implemented');
    }
}
