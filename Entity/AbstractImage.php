<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Ekyna\Bundle\CoreBundle\Model\ImageTrait;

/**
 * Class AbstractImage
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractImage implements ImageInterface
{
    use ImageTrait;

    /**
     * Id
     * 
     * @var integer
     */
    protected $id;

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
