<?php

namespace Ekyna\Bundle\CoreBundle\Service\Ui;

use Ekyna\Bundle\CoreBundle\Model\UiButton;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UiRenderer
 * @package Ekyna\Bundle\CoreBundle\Service\Ui
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UiRenderer
{
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var array
     */
    private $config;

    /**
     * @var AssetExtension
     */
    private $asset;

    /**
     * @var \Twig_TemplateWrapper
     */
    private $template;

    /**
     * @var OptionsResolver
     */
    private $buttonOptionsResolver;


    /**
     * Constructor.
     *
     * @param \Twig_Environment $twig
     * @param array             $config
     */
    public function __construct(\Twig_Environment $twig, array $config)
    {
        $this->twig = $twig;
        $this->config = $config;
    }

    /**
     * Renders the content stylesheet link.
     *
     * @return string
     */
    public function renderContentStylesheets()
    {
        $output = '';

        foreach ($this->config['stylesheets']['contents'] as $path) {
            $output .= $this->buildStylesheetTag($path);
        }

        return $output;
    }

    /**
     * Renders the forms stylesheets links.
     *
     * @return string
     */
    public function renderFormsStylesheets()
    {
        $output = '';

        foreach ($this->config['stylesheets']['forms'] as $path) {
            $output .= $this->buildStylesheetTag($path);
        }

        return $output;
    }

    /**
     * Renders the fonts stylesheets links.
     *
     * @return string
     */
    public function renderFontsStylesheets()
    {
        $output = '';

        foreach ($this->config['stylesheets']['fonts'] as $path) {
            $output .= $this->buildStylesheetTag($path);
        }

        return $output;
    }

    /**
     * Renders the "no image" img.
     *
     * @param array $attributes
     *
     * @return string
     */
    public function renderNoImage(array $attributes = [])
    {
        return $this->getTemplate()->renderBlock('no_image', [
            'no_image_path' => $this->config['no_image_path'],
            'attr'          => $attributes,
        ]);
    }

    /**
     * Renders the link.
     *
     * @param                   $href
     * @param string            $label
     * @param array             $options
     * @param array             $attributes
     *
     * @return string
     */
    public function renderLink($href, $label = '', array $options = [], array $attributes = [])
    {
        $options['type'] = 'link';
        $options['path'] = $href;

        return $this->renderButton($label, $options, $attributes);
    }

    /**
     * Renders the button.
     *
     * @param UiButton|string $label
     * @param array           $options
     * @param array           $attributes
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function renderButton($label = '', array $options = [], array $attributes = [])
    {
        if ($label instanceof UiButton) {
            $options = $label->getOptions();
            $attributes = $label->getAttributes();
            $label = $label->getLabel();
        }

        $options = $this->getButtonOptionsResolver()->resolve($options);

        $tag = 'button';
        $classes = ['btn', 'btn-' . $options['theme'], 'btn-' . $options['size']];
        $defaultAttributes = [
            'class' => sprintf('btn btn-%s btn-%s', $options['theme'], $options['size']),
        ];
        if ($options['type'] == 'link') {
            if (0 == strlen($options['path'])) {
                throw new \InvalidArgumentException('"path" option must be defined for "link" button type.');
            }
            $tag = 'a';
            $defaultAttributes['href'] = $options['path'];
        } else {
            $defaultAttributes['type'] = $options['type'];
        }

        if (array_key_exists('class', $attributes)) {
            $classes = array_merge($classes, explode(' ', $attributes['class']));
            unset($attributes['class']);
        }
        $defaultAttributes['class'] = implode(' ', $classes);
        $attributes = array_merge($defaultAttributes, $attributes);

        $icon = '';
        if (0 < strlen($options['icon'])) {
            $icon = $options['fa_icon'] ? 'fa fa-' . $options['icon'] : 'glyphicon glyphicon-' . $options['icon'];
        }

        return $this->getTemplate()->renderBlock('button', [
            'tag'   => $tag,
            'attr'  => $attributes,
            'label' => $label,
            'icon'  => $icon,
        ]);
    }

    /**
     * Renders the locale switcher.
     *
     * @param string $label
     * @param array  $actions
     * @param string $theme
     * @param string $size
     * @param bool   $right
     *
     * @return string
     */
    public function renderButtonDropdown($label, array $actions, $theme = 'default', $size = 'sm', $right = false)
    {
        // TODO validate actions : label => path

        return $this->getTemplate()->renderBlock('button_dropdown', [
            'label'   => $label,
            'theme'   => $theme,
            'size'    => $size,
            'actions' => $actions,
            'right'   => $right,
        ]);
    }

    /**
     * Builds a stylesheet tag.
     *
     * @param $path
     *
     * @return string
     */
    public function buildStylesheetTag(string $path)
    {
        return '<link href="' . $this->getAssetUrl($path) . '" rel="stylesheet" type="text/css">' . "\n";
    }

    /**
     * Returns the button options resolver.
     *
     * @return OptionsResolver
     */
    private function getButtonOptionsResolver()
    {
        if (null === $this->buttonOptionsResolver) {
            $this->buttonOptionsResolver = new OptionsResolver();
            $this->buttonOptionsResolver
                ->setDefaults([
                    'type'    => 'button',
                    'theme'   => 'default',
                    'size'    => 'sm',
                    'icon'    => null,
                    'fa_icon' => false,
                    'path'    => null,
                ])
                ->setRequired(['type', 'theme', 'size'])
                ->setAllowedValues('type', ['link', 'button', 'submit', 'reset'])
                ->setAllowedValues('theme', ['default', 'primary', 'success', 'warning', 'danger'])
                ->setAllowedValues('size', ['xs', 'sm', 'md', 'lg'])
                ->setAllowedTypes('icon', ['string', 'null'])
                ->setAllowedTypes('fa_icon', 'bool')
                ->setAllowedTypes('path', ['string', 'null']);
        }

        return $this->buttonOptionsResolver;
    }

    /**
     * Returns the controls template.
     *
     * @return \Twig_TemplateWrapper
     */
    private function getTemplate()
    {
        if ($this->template) {
            return $this->template;
        }

        return $this->template = $this->twig->load($this->config['controls_template']);
    }

    /**
     * Returns the asset url.
     *
     * @param string $path
     *
     * @return string
     */
    private function getAssetUrl(string $path)
    {
        if (!$this->asset) {
            $this->asset = $this->twig->getExtension(AssetExtension::class);
        }

        return $this->asset->getAssetUrl($path);
    }
}