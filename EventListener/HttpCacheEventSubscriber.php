<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvent;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvents;
use Ekyna\Bundle\CoreBundle\HttpCache\TagManager;
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
     * @var TagManager
     */
    protected $tagManager;

    /**
     * @var array
     */
    protected $responseTags;

    /**
     * Constructor.
     *
     * @param TagManager $tagManager
     */
    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
        $this->reset();
    }

    /**
     * Tag response event handler.
     *
     * @param HttpCacheEvent $event
     */
    public function onTagResponse(HttpCacheEvent $event)
    {
        $tags = $event->getData();

        if (empty($tags)) {
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
        $this->tagManager->invalidateTags($event->getData());
    }

    /**
     * Kernel response event handler.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $this->tagManager->tagResponse($event->getResponse(), $this->responseTags);
    }

    /**
     * Resets the response tags.
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
