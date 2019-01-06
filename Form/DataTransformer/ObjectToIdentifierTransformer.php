<?php

namespace Ekyna\Bundle\CoreBundle\Form\DataTransformer;

use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class ObjectToIdentifierTransformer
 * @package Ekyna\Bundle\CoreBundle\Form\DataTransformer
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ObjectToIdentifierTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var bool
     */
    protected $multiple;


    /**
     * Constructor.
     *
     * @param ObjectRepository $repository
     * @param string           $identifier
     * @param bool             $multiple
     */
    public function __construct(ObjectRepository $repository = null, string $identifier = 'id', bool $multiple = false)
    {
        $this->repository = $repository;
        $this->identifier = $identifier;
        $this->multiple = $multiple;
    }

    /**
     * Sets the repository.
     *
     * @param ObjectRepository $repository
     *
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
     *
     * @return ObjectToIdentifierTransformer
     */
    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Sets the multiple.
     *
     * @param bool $multiple
     *
     * @return ObjectToIdentifierTransformer
     */
    public function setMultiple(bool $multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        if (null === $value) {
            return $this->multiple ? [] : '';
        }

        $accessor = PropertyAccess::createPropertyAccessor();
        $class = $this->repository->getClassName();

        $transformer = function ($entity) use ($class, $accessor) {
            if (!$entity instanceof $class) {
                throw new UnexpectedTypeException($entity, $class);
            }

            $identifier = $accessor->getValue($entity, $this->identifier);

            if (empty($identifier)) {
                throw new TransformationFailedException(sprintf(
                    'Object "%s" identifier "%s" is empty.',
                    $class,
                    $this->identifier
                ));
            }

            return $accessor->getValue($entity, $this->identifier);
        };

        if ($this->multiple) {
            $transformed = [];

            if (!is_iterable($value)) {
                throw new UnexpectedTypeException($value, 'iterable');
            }

            foreach ($value as $entity) {
                $transformed[] = $transformer($entity);
            }

            return $transformed;
        }

        return $transformer($value);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (empty($value)) {
            return $this->multiple ? [] : null;
        }

        $transformer = function($identifier) {
            if (null === $entity = $this->repository->findOneBy([$this->identifier => $identifier])) {
                throw new TransformationFailedException(sprintf(
                    'Object "%s" with identifier "%s"="%s" does not exist.',
                    $this->repository->getClassName(),
                    $this->identifier,
                    $identifier
                ));
            }

            return $entity;
        };

        if ($this->multiple) {
            $transformed = [];

            if (!is_array($value)) {
                throw new UnexpectedTypeException($value, 'array');
            }

            foreach ($value as $identifier) {
                $transformed[] = $transformer($identifier);
            }

            return $transformed;
        }

        return $transformer($value);
    }
}
