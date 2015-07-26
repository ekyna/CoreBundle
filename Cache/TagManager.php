<?php

namespace Ekyna\Bundle\CoreBundle\Cache;

use Ekyna\Bundle\CoreBundle\Model\TaggedEntityInterface;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;

/**
 * Class TagManager
 * @package Ekyna\Bundle\CoreBundle\Cache
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TagManager
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * @var PropertyAccessor
     */
    protected $propertyAccessor;


    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * Sets the cache manager.
     *
     * @param CacheManager $cacheManager
     */
    public function setCacheManager(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * Builds the entity tag.
     *
     * @param object $entity
     * @param string $property
     * @return string
     */
    public function buildEntityTag($entity, $property = 'id')
    {
        if ($entity instanceof TaggedEntityInterface) {
            $prefix = $entity->getEntityTagPrefix();
        } else {
            $prefix = get_class($entity);
        }

        $value = $this->propertyAccessor->getValue($entity, $property);

        return sprintf('%s[%s:%s]', $prefix, $property, $value);
    }

    /**
     * Invalidates tags.
     *
     * @param mixed $tags
     * @throws \Exception
     * @throws \FOS\HttpCache\Exception\ExceptionCollection
     */
    public function invalidateTags($tags)
    {
        if (empty($tags) || !$this->isEnabled()) {
            return;
        }

        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $tags = $this->encodeTags($tags);

        $this->cacheManager->invalidateTags($tags)->flush();
    }

    /**
     * Adds tags to the response.
     *
     * @param Response $response
     * @param mixed $tags
     */
    public function tagResponse(Response $response, array $tags)
    {
        if (empty($tags) || !$this->isEnabled()) {
            return;
        }

        $tags = $this->encodeTags($tags);

        $this->cacheManager->tagResponse($response, $tags);
    }

    /**
     * Encodes the tags.
     *
     * @param array $tags
     * @return array
     */
    private function encodeTags(array $tags)
    {
        if ($this->config['tag']['encode']) {
            $tmp = [];
            foreach ($tags as $tag) {
                $tmp[] = hash('crc32b', $this->config['tag']['secret'].$tag, false);
            }
            return $tmp;
        }
        return $tags;
    }

    /**
     * Returns whether the tag management is active or not.
     *
     * @return bool
     */
    private function isEnabled()
    {
        return $this->config['enable'] && null !== $this->cacheManager;
    }
}
