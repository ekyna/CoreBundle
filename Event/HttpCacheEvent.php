<?php

namespace Ekyna\Bundle\CoreBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class HttpCacheEvent
 * @package Ekyna\Bundle\CoreBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class HttpCacheEvent extends Event
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * Constructor.
     *
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Returns the data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }
}
