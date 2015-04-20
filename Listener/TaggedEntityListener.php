<?php

namespace Ekyna\Bundle\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
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
     * On flush event handler.
     *
     * @param OnFlushEventArgs $eventArgs
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            if ($entity instanceof TaggedEntityInterface) {
                $this->addTagToInvalidate($entity::getEntityTagPrefix());
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof TaggedEntityInterface) {
                $this->addTagToInvalidate($entity::getEntityTagPrefix());
                $this->addTagToInvalidate($entity->getEntityTag());
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            if ($entity instanceof TaggedEntityInterface) {
                $this->addTagToInvalidate($entity::getEntityTagPrefix());
                $this->addTagToInvalidate($entity->getEntityTag());
            }
        }

        /** @var \Doctrine\Common\Collections\ArrayCollection $col */
        foreach ($uow->getScheduledCollectionUpdates() as $col) {
            foreach ($col as $entity) {
                if ($entity instanceof TaggedEntityInterface) {
                    if (null !== $entity->getId()) {
                        $this->addTagToInvalidate($entity::getEntityTagPrefix());
                        $this->addTagToInvalidate($entity->getEntityTag());
                    }
                }
            }
        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {
            foreach ($col as $entity) {
                if ($entity instanceof TaggedEntityInterface) {
                    if (null !== $entity->getId()) {
                        $this->addTagToInvalidate($entity::getEntityTagPrefix());
                        $this->addTagToInvalidate($entity->getEntityTag());
                    }
                }
            }
        }
    }

    /**
     * Post flush event handler.
     *
     * @param PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
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
            Events::onFlush,
            Events::postFlush,
        );
    }
}
