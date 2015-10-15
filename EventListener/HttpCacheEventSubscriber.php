<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Cache\TagManager;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvent;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvents;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
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
     * Constructor.
     *
     * @param TagManager $tagManager
     */
    public function __construct(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
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

        $this->tagManager->addTags($tags);
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

        $this->tagManager->invalidateTags($tags);
    }

    /**
     * Kernel response event handler.
     */
    public function onKernelResponse()
    {
        $this->tagManager->flush();
    }

    /**
     * Kernel terminate event handler.
     */
    public function onKernelTerminate()
    {
        $this->tagManager->flush();
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
