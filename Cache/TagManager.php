<?php

namespace Ekyna\Bundle\CoreBundle\Cache;

use FOS\HttpCacheBundle\Handler\TagHandler;

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
     * @var TagHandler
     */
    protected $tagHandler;

    /**
     * @var array
     */
    protected $responseTags;

    /**
     * @var array
     */
    protected $invalidateTags;


    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        $this->reset();
    }

    /**
     * Sets the tag handler.
     *
     * @param TagHandler $tagHandler
     */
    public function setTagHandler(TagHandler $tagHandler)
    {
        $this->tagHandler = $tagHandler;
    }

    /**
     * Invalidates tags.
     *
     * @param mixed $tags
     */
    public function invalidateTags($tags)
    {
        if (empty($tags) || !$this->isEnabled()) {
            return;
        }

        $tags = $this->encodeTags($tags);

        foreach ($tags as $tag) {
            if (!in_array($tag, $this->invalidateTags)) {
                $this->invalidateTags[] = $tag;
            }
        }
    }

    /**
     * Adds tags (for the response).
     *
     * @param mixed $tags
     */
    public function addTags($tags)
    {
        if (empty($tags) || !$this->isEnabled()) {
            return;
        }

        $tags = $this->encodeTags($tags);

        foreach ($tags as $tag) {
            if (!in_array($tag, $this->responseTags)) {
                $this->responseTags[] = $tag;
            }
        }
    }

    /**
     * Flushes the tag manager.
     */
    public function flush()
    {
        if (!empty($this->invalidateTags)) {
            $this->tagHandler->invalidateTags($this->invalidateTags);
        }

        if (!empty($this->responseTags)) {
            $this->tagHandler->addTags($this->responseTags);
        }

        $this->reset();
    }

    /**
     * Rests the tags arrays.
     */
    private function reset()
    {
        $this->responseTags = [];
        $this->invalidateTags = [];
    }

    /**
     * Encodes the tags.
     *
     * @param mixed $tags
     * @return array
     */
    private function encodeTags($tags)
    {
        if (!is_array($tags)) {
            $tags = array($tags);
        }

        if ($this->config['tag']['encode']) {
            $tmp = [];
            foreach ($tags as $tag) {
                $tmp[] = hash('crc32b', $this->config['tag']['secret'].$tag, false);
            }
            $tags = $tmp;
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
        return $this->config['enable'] && null !== $this->tagHandler;
    }
}
