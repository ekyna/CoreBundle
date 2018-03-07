<?php

namespace Ekyna\Bundle\CoreBundle\Modal;

use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Modal
 * @package Ekyna\Bundle\CoreBundle\Modal
 * @author  Étienne Dauvergne <contact@ekyna.com>
 * @see     http://nakupanda.github.io/bootstrap3-dialog/#available-options
 */
class Modal
{
    const TYPE_DEFAULT = 'type-default';
    const TYPE_INFO    = 'type-info';
    const TYPE_PRIMARY = 'type-primary';
    const TYPE_SUCCESS = 'type-success';
    const TYPE_WARNING = 'type-warning';
    const TYPE_DANGER  = 'type-danger';

    const SIZE_NORMAL = 'size-normal';
    const SIZE_SMALL  = 'size-small';
    const SIZE_WIDE   = 'size-wide';    // size-wide is equal to modal-lg
    const SIZE_LARGE  = 'size-large';

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
     * @var bool
     */
    protected $condensed = false;

    /**
     * @var string
     */
    protected $cssClass;

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
    protected $vars = [];

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
    public function __construct($title = null, $content = null, array $buttons = [])
    {
        $this->setTitle($title);
        $this->setContent($content);
        $this->setButtons($buttons);

        $this->setVars([
            'form_template' => 'EkynaCoreBundle:Form:default_form_body.html.twig',
        ]);
    }

    /**
     * Sets the type.
     *
     * @param string $type
     *
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
     *
     * @return Modal
     */
    public function setSize($size)
    {
        static::validateSize($size);

        $this->size = $size;

        return $this;
    }

    /**
     * Sets whether to add the condensed css class.
     *
     * @param bool $condensed
     *
     * @return Modal
     */
    public function setCondensed($condensed)
    {
        $this->condensed = $condensed;

        return $this;
    }

    /**
     * Sets the css class.
     *
     * @param string $class
     *
     * @return Modal
     */
    public function setCssClass($class)
    {
        $this->cssClass = $class;

        return $this;
    }

    /**
     * Sets the title.
     *
     * @param string $title
     *
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
     *
     * @return Modal
     */
    public function setContent($content)
    {
        if ($content instanceof FormView) {
            $this->contentType = 'form';
        } elseif (class_exists('Ekyna\Component\Table\View\TableView') && is_a($content, 'Ekyna\Component\Table\View\TableView')) {
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
     *
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
     *
     * @return Modal
     */
    public function setButtons(array $buttons)
    {
        $resolver = static::getButtonOptionsResolver();
        $tmp = [];
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
     * @param bool  $prepend
     *
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
        $classes = explode(' ', $this->cssClass);

        return [
            'size'      => $this->size,
            'type'      => $this->type,
            'cssClass'  => implode(' ', $classes),
            'condensed' => $this->condensed,
        ];
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
                ->setDefaults([
                    'id'       => null,
                    'icon'     => null,
                    'label'    => null,
                    'action'   => null,
                    'autospin' => null,
                    'cssClass' => 'btn-default',
                    'hotkey'   => null,
                ])
                ->setAllowedTypes('id', 'string')
                ->setAllowedTypes('icon', ['null', 'string'])
                ->setAllowedTypes('label', 'string')
                ->setAllowedTypes('action', ['null', 'string'])
                ->setAllowedTypes('autospin', ['null', 'bool'])
                ->setAllowedTypes('cssClass', 'string')
                ->setAllowedTypes('hotkey', ['null', 'int'])
                ->setAllowedValues('action', function ($value) {
                    if (null === $value) {
                        return true;
                    }

                    return preg_match('~^function\s?\((dialog)?\)\s?\{[^}]+\}$~', $value);
                });
        }

        return static::$buttonOptionsResolver;
    }

    /**
     * Validates the modal type.
     *
     * @param string $type
     * @param bool   $throwException
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    static public function validateType($type, $throwException = true)
    {
        if (in_array($type, [
            self::TYPE_DEFAULT,
            self::TYPE_INFO,
            self::TYPE_PRIMARY,
            self::TYPE_SUCCESS,
            self::TYPE_WARNING,
            self::TYPE_DANGER,
        ])) {
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
     * @param bool   $throwException
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    static public function validateSize($size, $throwException = true)
    {
        if (in_array($size, [
            self::SIZE_NORMAL,
            self::SIZE_SMALL,
            self::SIZE_WIDE,
            self::SIZE_LARGE,
        ])) {
            return true;
        }

        if ($throwException) {
            throw new \InvalidArgumentException(sprintf('Invalid modal size "%s".', $size));
        }

        return false;
    }
}
