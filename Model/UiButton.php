<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Class UiButton
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UiButton
{
    /**
     * @var string
     */
    private $label;

    /**
     * @var array
     */
    private $options;

    /**
     * @var array
     */
    private $attributes;

    /**
     * @var int
     */
    private $priority;


    /**
     * Constructor.
     *
     * @param string $label
     * @param array  $options
     * @param array  $attributes
     * @param int    $priority
     */
    public function __construct(string $label, array $options = [], array $attributes = [], int $priority = 0)
    {
        $this->label = $label;
        $this->options = $options;
        $this->attributes = $attributes;
        $this->priority = $priority;
    }

    /**
     * Returns the label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the label.
     *
     * @param string $label
     *
     * @return UiButton
     */
    public function setLabel(string $label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Returns the options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Sets the options.
     *
     * @param array $options
     *
     * @return UiButton
     */
    public function setOptions(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Returns the attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Sets the attributes.
     *
     * @param array $attributes
     *
     * @return UiButton
     */
    public function setAttributes(array $attributes = [])
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Returns the priority.
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Sets the priority.
     *
     * @param int $priority
     *
     * @return UiButton
     */
    public function setPriority(int $priority)
    {
        $this->priority = $priority;

        return $this;
    }
}
