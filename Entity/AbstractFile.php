<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model;

/**
 * Class AbstractFile
 * @package Ekyna\Bundle\CoreBundle\Entity
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractFile implements Model\FileInterface
{
    use Model\UploadableTrait;

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
