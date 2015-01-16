<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvent;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvents;
use FOS\HttpCacheBundle\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class HttpCacheEventSubscriber
 * @package Ekyna\Bundle\CoreBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class HttpCacheEventSubscriber implements EventSubscriberInterface
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
     * @var array
     */
    protected $responseTags;

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
     * Sets the cache manager.
     *
     * @param CacheManager $cacheManager
     */
    public function setCacheManager(CacheManager $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * Tag response event handler.
     *
     * @param HttpCacheEvent $event
     */
    public function onTagResponse(HttpCacheEvent $event)
    {
        $tags = $event->getData();

        if (empty($tags) || null === $this->cacheManager) {
            return;
        }

        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $this->responseTags = array_merge($this->responseTags, $tags);
    }

    /**
     * Invalidate tags event handler.
     *
     * @param HttpCacheEvent $event
     */
    public function onInvalidateTag(HttpCacheEvent $event)
    {
        $tags = $event->getData();

        if (empty($tags) || null === $this->cacheManager) {
            return;
        }

        if (!is_array($tags)) {
            $tags = array($tags);
        }

        $tags = $this->encodeTags($tags);

        $this->cacheManager
            ->invalidateTags($tags)
            ->flush()
        ;
    }

    /**
     * Kernel response event handler.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (null !== $this->cacheManager && !empty($this->responseTags)) {
            $response = $event->getResponse();
            $tags = $this->encodeTags($this->responseTags);
            $this->cacheManager->tagResponse($response, $tags);
            $this->reset();
        }
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

    /**
     * Resets.
     */
    private function reset()
    {
        $this->responseTags = [];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            HttpCacheEvents::TAG_RESPONSE   => array('onTagResponse', 0),
            HttpCacheEvents::INVALIDATE_TAG => array('onInvalidateTag', 0),

            KernelEvents::RESPONSE          => array('onKernelResponse', 0),
        );
    }
}
