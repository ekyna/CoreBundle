<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model;

/**
 * Class AbstractImage
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractImage implements Model\ImageInterface
{
    use Model\ImageTrait;

    /**
     * Id
     *
     * @var integer
     */
    protected $id;


    /**
     * Returns the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return pathinfo($this->getPath(), PATHINFO_BASENAME);
    }

    /**
     * Get id
     *
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }
}
