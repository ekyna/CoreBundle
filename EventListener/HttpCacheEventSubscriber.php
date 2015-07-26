<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Cache\TagManager;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvent;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvents;
use Symfony\Component\Console\ConsoleEvents;
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
     * @var array
     */
    protected $invalidateTags;

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

        foreach ($tags as $tag) {
            if (!in_array($tag, $this->responseTags)) {
                $this->responseTags[] = $tag;
            }
        }
    }

    /**
     * Invalidate tags event handler.
     *
     * @param HttpCacheEvent $event
     */
    public function onInvalidateTag(HttpCacheEvent $event)
    {
        $tags = $event->getData();

        if (empty($tags)) {
            return;
        }

        if (!is_array($tags)) {
            $tags = array($tags);
        }

        foreach ($tags as $tag) {
            if (!in_array($tag, $this->invalidateTags)) {
                $this->invalidateTags[] = $tag;
            }
        }
    }

    /**
     * Kernel response event handler.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!empty($this->invalidateTags)) {
            $this->tagManager->invalidateTags($this->invalidateTags);
        }
        if (!empty($this->responseTags)) {
            $this->tagManager->tagResponse($event->getResponse(), $this->responseTags);
        }
        $this->reset();
    }

    /**
     * Kernel terminate event handler.
     */
    public function onKernelTerminate()
    {
        if (!empty($this->invalidateTags)) {
            $this->tagManager->invalidateTags($this->invalidateTags);
        }
        $this->reset();
    }

    /**
     * Resets the response tags.
     */
    private function reset()
    {
        $this->responseTags = [];
        $this->invalidateTags = [];
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

            KernelEvents::TERMINATE         => array('onKernelTerminate', 0),
            ConsoleEvents::TERMINATE        => array('onKernelTerminate', 0),
        );
    }
}
