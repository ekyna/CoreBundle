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
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ObjectToIdentifierTransformer implements DataTransformerInterface
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
        if (null === $value) {
            return '';
        }

        $class = $this->repository->getClassName();

        if (!$value instanceof $class) {
            throw new UnexpectedTypeException($value, $class);
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        return $accessor->getValue($value, $this->identifier);
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        if (null === $entity = $this->repository->findOneBy(array($this->identifier => $value))) {
            throw new TransformationFailedException(sprintf(
                'Object "%s" with identifier "%s"="%s" does not exist.',
                $this->repository->getClassName(),
                $this->identifier,
                $value
            ));
        }

        return $entity;
    }
}
