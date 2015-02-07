<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Trait ImageTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait ImageTrait
{
    use UploadableTrait;

    /**
     * Alternative text
     *
     * @var string
     */
    protected $alt;


    /**
     * Returns the alternative text.
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Sets the alternative text.
     *
     * @param string $alt
     * @return ImageTrait|$this
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        return $this;
    }
}
