<?php

namespace Ekyna\Bundle\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvent;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvents;
use Ekyna\Bundle\CoreBundle\Model\TaggedEntityInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TaggedEntityListener
 * @package Ekyna\Bundle\CoreBundle\Listener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TaggedEntityListener implements EventSubscriber
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var array
     */
    protected $tagsToInvalidate;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->reset();
    }

    /**
     * Post update event handler.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof TaggedEntityInterface) {
            $this->addTagToInvalidate($entity->getEntityTag());
        }
    }

    /**
     * Post remove event handler.
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getObject();

        if ($entity instanceof TaggedEntityInterface) {
            $this->addTagToInvalidate($entity->getEntityTag());
        }
    }

    /**
     * Post flush event handler.
     *
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $this->eventDispatcher->dispatch(
            HttpCacheEvents::INVALIDATE_TAG,
            new HttpCacheEvent($this->tagsToInvalidate)
        );
        $this->reset();
    }

    /**
     * Adds tag to invalidate.
     *
     * @param string $tag
     */
    private function addTagToInvalidate($tag)
    {
        if (0 < strlen($tag) && !in_array($tag, $this->tagsToInvalidate)) {
            $this->tagsToInvalidate[] = $tag;
        }
    }

    /**
     * Resets the tags to invalidate.
     */
    private function reset()
    {
        $this->tagsToInvalidate = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::postUpdate,
            Events::postRemove,
            Events::postFlush,
        );
    }
}
