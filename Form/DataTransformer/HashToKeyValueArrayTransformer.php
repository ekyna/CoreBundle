<?php

namespace Ekyna\Bundle\CoreBundle\Form\DataTransformer;

use Ekyna\Bundle\CoreBundle\Model\KeyValueContainer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Class HashToKeyValueArrayTransformer
 * @package Ekyna\Bundle\CoreBundle\Form\DataTransformer
 * @author Bart van den Burg <bart@burgov.nl>
 * @see https://github.com/Burgov/KeyValueFormBundle/blob/master/Form/DataTransformer/HashToKeyValueArrayTransformer.php
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class HashToKeyValueArrayTransformer implements DataTransformerInterface
{
    /**
     * @var bool
     */
    private $useContainerObject;

    /**
     * @param bool $useContainerObject Whether to return a KeyValueContainer object or simply an array
     */
    public function __construct($useContainerObject)
    {
        $this->useContainerObject = $useContainerObject;
    }

    /**
     * Doing the transformation here would be too late for the collection type to do it's resizing magic, so
     * instead it is done in the forms PRE_SET_DATA listener
     *
     * @param KeyValueContainer|array $value
     * @return KeyValueContainer|array
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * @param array $value
     * @return KeyValueContainer|array
     * @throws \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function reverseTransform($value)
    {
        $return = $this->useContainerObject ? new KeyValueContainer() : array();

        foreach ($value as $data) {
            if (array('key', 'value') != array_keys($data)) {
                throw new TransformationFailedException();
            }
            if (array_key_exists($data['key'], $return)) {
                throw new TransformationFailedException('Duplicate key detected');
            }
            $return[$data['key']] = $data['value'];
        }

        return $return;
    }
}
