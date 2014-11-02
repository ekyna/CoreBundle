<?php

namespace Ekyna\Bundle\CoreBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class IdentifierToObjectTransformer
 * @package Ekyna\Bundle\CoreBundle\Form\DataTransformer
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class IdentifierToObjectTransformer implements DataTransformerInterface
{
    /**
     * Repository
     *
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * Identifier
     *
     * @var string
     */
    protected $identifier;

    /**
     * Constructor.
     *
     * @param ObjectRepository $repository
     * @param string           $identifier
     */
    public function __construct(ObjectRepository $repository = null, $identifier = 'id')
    {
        $this->repository = $repository;
        $this->identifier = $identifier;
    }

    /**
     * Sets the repository.
     *
     * @param ObjectRepository $repository
     * @return ObjectToIdentifierTransformer
     */
    public function setRepository(ObjectRepository $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * Sets the identifier.
     *
     * @param string $identifier
     * @return ObjectToIdentifierTransformer
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (empty($value)) {
            return null;
        }

        if (is_array($value)) {
            if (null === $entities = $this->repository->findBy(array($this->identifier => $value))) {
                throw new TransformationFailedException(sprintf(
                    'Objects "%s" could not be converted from value "%" with identifier "%s".',
                    $this->repository->getClassName(),
                    implode(', ', $value),
                    $this->identifier
                ));
            } elseif (count($entities) !== count($value)) {
                throw new TransformationFailedException(sprintf(
                    'One or more objects "%s" could not be converted from value "%s" with identifier "%s".',
                    $this->repository->getClassName(),
                    implode(', ', $value),
                    $this->identifier
                ));
            } else {
                return $entities;
            }
        } elseif (null === $entity = $this->repository->findOneBy(array($this->identifier => $value))) {
            throw new TransformationFailedException(sprintf(
                'Object "%s" with identifier "%s"="%s" does not exist.',
                $this->repository->getClassName(),
                $this->identifier,
                $value
            ));
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (null === $value) {
            return '';
        }

        $class = $this->repository->getClassName();
        $accessor = PropertyAccess::createPropertyAccessor();

        if ($value instanceof ArrayCollection) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            $identifiers = [];
            foreach($value as $entity) {
                if (!$entity instanceof $class) {
                    throw new UnexpectedTypeException($entity, $class);
                }
                $identifiers[] = $accessor->getValue($entity, $this->identifier);
            }
            return $identifiers;
        } elseif (!$value instanceof $class) {
            throw new UnexpectedTypeException($value, $class);
        }

        return $accessor->getValue($value, $this->identifier);
    }
}
