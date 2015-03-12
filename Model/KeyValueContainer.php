<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Class KeyValueContainer
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Bart van den Burg <bart@burgov.nl>
 * @see https://github.com/Burgov/KeyValueFormBundle/blob/master/KeyValueContainer.php
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class KeyValueContainer implements \ArrayAccess
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->data[$offset] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}
