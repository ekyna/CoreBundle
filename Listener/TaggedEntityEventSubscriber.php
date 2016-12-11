<?php

namespace Ekyna\Bundle\CoreBundle\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Ekyna\Component\Resource\Model\TranslationInterface;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvent;
use Ekyna\Bundle\CoreBundle\Event\HttpCacheEvents;
use Ekyna\Component\Resource\Model\TaggedEntityInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class TaggedEntityEventSubscriber
 * @package Ekyna\Bundle\CoreBundle\Listener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class TaggedEntityEventSubscriber implements EventSubscriber
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
            $this->invalidateEntity($entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->invalidateEntity($entity);
        }

        foreach ($uow->getScheduledEntityDeletions() as $entity) {
            $this->invalidateEntity($entity);
        }

        /** @var \Doctrine\Common\Collections\ArrayCollection $col */
        foreach ($uow->getScheduledCollectionUpdates() as $col) {
            foreach ($col as $entity) {
                $this->invalidateEntity($entity);
            }
        }

        foreach ($uow->getScheduledCollectionDeletions() as $col) {
            foreach ($col as $entity) {
                $this->invalidateEntity($entity);
            }
        }
    }

    /**
     * Post flush event handler.
     */
    public function postFlush()
    {
        $this->eventDispatcher->dispatch(
            HttpCacheEvents::INVALIDATE_TAG,
            new HttpCacheEvent($this->tagsToInvalidate)
        );
        $this->reset();
    }

    /**
     * Invalidates the entity http cache.
     *
     * @param object $entity
     */
    private function invalidateEntity($entity)
    {
        if ($entity instanceof TaggedEntityInterface) {
            $this->addTagToInvalidate($entity::getEntityTagPrefix());
            if (null !== $entity->getId()) {
                $this->addTagToInvalidate($entity->getEntityTag());
            }
        } elseif ($entity instanceof TranslationInterface) {
            $translatable = $entity->getTranslatable();
            $this->invalidateEntity($translatable);
        }
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
        return [
            Events::onFlush,
            Events::postFlush,
        ];
    }
}
