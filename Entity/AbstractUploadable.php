<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model\UploadableTrait;

/**
 * Class AbstractUploadable
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractUploadable
{
    use UploadableTrait;

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
