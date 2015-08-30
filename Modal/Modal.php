<?php

namespace Ekyna\Bundle\CoreBundle\Modal;

use Ekyna\Component\Table\TableView;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Modal
 * @package Ekyna\Bundle\CoreBundle\Modal
 * @author Étienne Dauvergne <contact@ekyna.com>
 * @see http://nakupanda.github.io/bootstrap3-dialog/#available-options
 */
class Modal
{
    const TYPE_DEFAULT = 'type-default';
    const TYPE_INFO    = 'type-info';
    const TYPE_PRIMARY = 'type-primary';
    const TYPE_SUCCESS = 'type-success';
    const TYPE_WARNING = 'type-warning';
    const TYPE_DANGER  = 'type-danger';

    const SIZE_NORMAL  = 'size-normal';
    const SIZE_SMALL   = 'size-small';
    const SIZE_WIDE    = 'size-wide';    // size-wide is equal to modal-lg
    const SIZE_LARGE   = 'size-large';

    /**
     * @var OptionsResolver
     */
    static protected $buttonOptionsResolver;

    /**
     * @var string
     */
    protected $type = self::TYPE_DEFAULT;

    /**
     * @var string
     */
    protected $size = self::SIZE_WIDE;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var mixed
     */
    protected $content;

    /**
     * @var array
     */
    protected $vars = array();

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var array
     */
    protected $buttons;

    /**
     * Constructor.
     *
     * @param string $title
     * @param mixed  $content
     * @param array  $buttons
     */
    public function __construct($title = null, $content = null, array $buttons = array())
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setButtons($buttons);
    }

    /**
     * Sets the type.
     *
     * @param string $type
     * @return Modal
     */
    public function setType($type)
    {
        static::validateType($type);

        $this->type = $type;
        return $this;
    }

    /**
     * Sets the size.
     *
     * @param string $size
     * @return Modal
     */
    public function setSize($size)
    {
        static::validateSize($size);

        $this->size = $size;
        return $this;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     * @return Modal
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets the content.
     *
     * @param mixed $content
     * @return Modal
     */
    public function setContent($content)
    {
        if ($content instanceof FormView) {
            $this->contentType = 'form';
        } elseif ($content instanceof TableView) {
            $this->contentType = 'table';
        } elseif (is_array($content)) {
            $this->contentType = 'data';
        } else {
            $this->contentType = 'html';
        }
        $this->content = $content;
        return $this;
    }

    /**
     * Returns the content.
     *
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the vars.
     *
     * @param array $vars
     * @return Modal
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * Returns the vars.
     *
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Returns the contentType.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Sets the buttons.
     *
     * @param array $buttons
     * @return Modal
     */
    public function setButtons(array $buttons)
    {
        $resolver = static::getButtonOptionsResolver();
        $tmp = array();
        foreach ($buttons as $options) {
            $tmp[] = $resolver->resolve($options);
        }
        $this->buttons = $tmp;
        return $this;
    }

    /**
     * Adds the button.
     *
     * @param array $options
     * @param bool $prepend
     * @return Modal
     */
    public function addButton(array $options, $prepend = false)
    {
        $resolver = static::getButtonOptionsResolver();
        $options = $resolver->resolve($options);
        if ($prepend) {
            array_unshift($this->buttons, $options);
        } else {
            array_push($this->buttons, $options);
        }
        return $this;
    }

    /**
     * Returns the buttons.
     *
     * @return array
     */
    public function getButtons()
    {
        return $this->buttons;
    }

    /**
     * Returns the buttons.
     *
     * @return array
     */
    public function getConfig()
    {
        return array(
            'size' => $this->size,
            'type' => $this->type,
        );
    }

    /**
     * Returns the button options resolver.
     *
     * @return OptionsResolver
     */
    static protected function getButtonOptionsResolver()
    {
        if (null === static::$buttonOptionsResolver) {
            static::$buttonOptionsResolver = new OptionsResolver();
            static::$buttonOptionsResolver
                ->setDefaults(array(
                    'id'       => null,
                    'icon'     => null,
                    'label'    => null,
                    'autospin' => null,
                    'cssClass' => 'btn-default',
                    'hotkey'   => null,
                ))
                ->setAllowedTypes(array(
                    'id'       => 'string',
                    'icon'     => array('null', 'string'),
                    'label'    => 'string',
                    'autospin' => array('null', 'bool'),
                    'cssClass' => 'string',
                    'hotkey'   => array('null', 'int'),
                ))
            ;
        }
        return static::$buttonOptionsResolver;
    }

    /**
     * Validates the modal type.
     *
     * @param string $type
     * @param bool $throwException
     * @return bool
     * @throws \InvalidArgumentException
     */
    static public function validateType($type, $throwException = true)
    {
        if (in_array($type, array(
            self::TYPE_DEFAULT,
            self::TYPE_INFO,
            self::TYPE_PRIMARY,
            self::TYPE_SUCCESS,
            self::TYPE_WARNING,
            self::TYPE_DANGER,
        ))) {
            return true;
        }

        if ($throwException) {
            throw new \InvalidArgumentException(sprintf('Invalid modal type "%s".', $type));
        }

        return false;
    }

    /**
     * Validates the modal size.
     *
     * @param string $size
     * @param bool $throwException
     * @return bool
     * @throws \InvalidArgumentException
     */
    static public function validateSize($size, $throwException = true)
    {
        if (in_array($size, array(
            self::SIZE_NORMAL,
            self::SIZE_SMALL,
            self::SIZE_WIDE,
            self::SIZE_LARGE,
        ))) {
            return true;
        }

        if ($throwException) {
            throw new \InvalidArgumentException(sprintf('Invalid modal size "%s".', $size));
        }

        return false;
    }
}
