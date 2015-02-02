<?php

namespace Ekyna\Bundle\CoreBundle\HttpCache;

use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TagManager
 * @package Ekyna\Bundle\CoreBundle\HttpCache
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
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
     * Invalidates tags.
     *
     * @param mixed $tags
     * @throws \Exception
     * @throws \FOS\HttpCache\Exception\ExceptionCollection
     */
    public function invalidateTags($tags)
    {
        if (empty($tags) || null === $this->cacheManager) {
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
        if (empty($tags) || null === $this->cacheManager) {
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
                $tmp[] = hash('crc32', $this->config['tag']['secret'].$tag, false);
            }
            return $tmp;
        }
        return $tags;
    }
}
