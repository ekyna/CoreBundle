<?php

namespace Ekyna\Bundle\CoreBundle\Service\Ui;

use Ekyna\Bundle\CoreBundle\Model\FAIcons;
use Ekyna\Bundle\CoreBundle\Model\UiButton;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Bridge\Twig\Extension\HttpFoundationExtension;
use Symfony\Component\Asset\Packages;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;
use Twig\TemplateWrapper;

/**
 * Class UiRenderer
 * @package Ekyna\Bundle\CoreBundle\Service\Ui
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class UiRenderer implements RuntimeExtensionInterface
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var Packages
     */
    private $packages;

    /**
     * @var array
     */
    private $config;

    /**
     * @var AssetExtension
     */
    private $assetExtension;

    /**
     * @var HttpFoundationExtension
     */
    private $httpExtension;

    /**
     * @var TemplateWrapper
     */
    private $template;

    /**
     * @var OptionsResolver
     */
    private $buttonOptionsResolver;

    /**
     * @var OptionsResolver
     */
    private $dropdownOptionsResolver;


    /**
     * Constructor.
     *
     * @param Environment $twig
     * @param Packages    $packages
     * @param array       $config
     */
    public function __construct(Environment $twig, Packages $packages, array $config)
    {
        $this->twig = $twig;
        $this->packages = $packages;
        $this->config = $config;
    }

    /**
     * Renders the content stylesheet link.
     *
     * @return string
     */
    public function renderContentStylesheets(): string
    {
        $output = '';

        foreach ($this->config['stylesheets']['contents'] as $path) {
            $output .= $this->buildStylesheetTag($path);
        }

        return $output;
    }

    /**
     * Builds a stylesheet tag.
     *
     * @param $path
     *
     * @return string
     */
    public function buildStylesheetTag(string $path): string
    {
        return '<link href="' . $this->getAssetUrl($path) . '" rel="stylesheet" type="text/css">' . "\n";
    }

    /**
     * Returns the asset url.
     *
     * @param string $path
     *
     * @return string
     */
    private function getAssetUrl(string $path): string
    {
        return $this->getHttpExtension()->generateAbsoluteUrl(
            $this->getAssetExtension()->getAssetUrl($path)
        );
    }

    /**
     * Renders the assets base url attribute.
     *
     * @return string
     */
    public function renderAssetsBaseUrl(): string
    {
        $url = substr($this->getAssetUrl('fake.css'), 0, -9);

        return ' data-asset-base-url="' . $url . '"';
    }

    /**
     * Renders the forms stylesheets links.
     *
     * @return string
     */
    public function renderFormsStylesheets(): string
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
    public function renderFontsStylesheets(): string
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
    public function renderNoImage(array $attributes = []): string
    {
        return $this->getTemplate()->renderBlock(
            'no_image',
            [
                'no_image_path' => $this->getAssetUrl($this->config['no_image_path']),
                'attr'          => $attributes,
            ]
        );
    }

    /**
     * Returns the controls template.
     *
     * @return TemplateWrapper
     */
    private function getTemplate(): TemplateWrapper
    {
        if ($this->template) {
            return $this->template;
        }

        return $this->template = $this->twig->load($this->config['controls_template']);
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
    public function renderLink($href, $label = '', array $options = [], array $attributes = []): string
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
    public function renderButton($label = '', array $options = [], array $attributes = []): string
    {
        if ($label instanceof UiButton) {
            $options = $label->getOptions();
            $attributes = $label->getAttributes();
            $label = $label->getLabel();
        } else {
            $label = (string)$label;
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
        if (!empty($options['icon'])) {
            $icon = ($options['fa_icon'] ? 'fa fa-' : 'glyphicon glyphicon-') . $options['icon'];
        }

        return $this->getTemplate()->renderBlock(
            'button',
            [
                'tag'   => $tag,
                'attr'  => $attributes,
                'label' => $label,
                'icon'  => $icon,
            ]
        );
    }

    /**
     * Returns the button options resolver.
     *
     * @return OptionsResolver
     */
    private function getButtonOptionsResolver(): OptionsResolver
    {
        if (null === $this->buttonOptionsResolver) {
            $this->buttonOptionsResolver = new OptionsResolver();
            $this->buttonOptionsResolver
                ->setDefaults(
                    [
                        'type'    => 'button',
                        'theme'   => 'default',
                        'size'    => 'sm',
                        'icon'    => null,
                        'fa_icon' => false,
                        'path'    => null,
                    ]
                )
                ->setRequired(['type', 'theme', 'size'])
                ->setAllowedValues('type', ['link', 'button', 'submit', 'reset'])
                ->setAllowedtypes('theme', 'string')
                ->setAllowedValues('size', ['xs', 'sm', 'md', 'lg'])
                ->setAllowedTypes('icon', ['string', 'null'])
                ->setAllowedTypes('fa_icon', 'bool')
                ->setAllowedTypes('path', ['string', 'null']);
        }

        return $this->buttonOptionsResolver;
    }

    /**
     * Renders the dropdown.
     *
     * @param array $actions
     * @param array $options
     * @param array $attributes
     *
     * @return string
     */
    public function renderDropdown(
        array $actions,
        array $options = [],
        array $attributes = []
    ): string {
        $options = $this->getDropdownOptionsResolver()->resolve($options);

        $classes = ['btn', 'btn-' . $options['theme'], 'btn-' . $options['size'], 'dropdown-toggle'];
        unset($options['theme'], $options['size']);

        if (array_key_exists('class', $attributes)) {
            array_push($classes, ...explode(' ', $attributes['class']));
            unset($attributes['class']);
        }
        $attributes = array_replace(
            $attributes,
            [
                'aria-expanded' => 'false',
                'aria-haspopup' => 'true',
                'class'         => implode(' ', $classes),
                'data-toggle'   => 'dropdown',
                'type'          => 'button',
            ]
        );

        if (!empty($options['icon'])) {
            $options['icon'] = ($options['fa_icon'] ? 'fa fa-' : 'glyphicon glyphicon-') . $options['icon'];
        }

        // TODO validate actions : label => path

        return $this->getTemplate()->renderBlock(
            'dropdown',
            array_replace(
                $options,
                [
                    'attr'    => $attributes,
                    'actions' => $actions,
                ]
            )
        );
    }

    /**
     * Returns the dropdown options resolver.
     *
     * @return OptionsResolver
     */
    private function getDropdownOptionsResolver(): OptionsResolver
    {
        if (null === $this->dropdownOptionsResolver) {
            $this->dropdownOptionsResolver = new OptionsResolver();
            $this->dropdownOptionsResolver
                ->setDefaults(
                    [
                        'label'   => null,
                        'theme'   => 'default',
                        'size'    => 'sm',
                        'icon'    => null,
                        'fa_icon' => false,
                        'right'   => false,
                    ]
                )
                ->setRequired(['theme', 'size'])
                ->setAllowedTypes('label', ['null', 'string'])
                ->setAllowedTypes('theme', 'string')
                ->setAllowedValues('size', ['xs', 'sm', 'md', 'lg'])
                ->setAllowedTypes('icon', ['string', 'null'])
                ->setAllowedTypes('fa_icon', 'bool')
                ->setAllowedTypes('right', 'bool');
        }

        return $this->dropdownOptionsResolver;
    }

    /**
     * Renders a font awesome icon.
     *
     * @param string|null $icon
     * @param string|null $classes
     *
     * @return string|null
     */
    public function renderFaIcon(string $icon = null, string $classes = null): ?string
    {
        if (is_null($icon) || !FAIcons::isValid($icon, false)) {
            return null;
        }

        return sprintf('<i class="fa fa-%s %s"></i>', $icon, $classes);
    }

    /**
     * Returns the asset twig extension.
     *
     * @return AssetExtension
     */
    private function getAssetExtension(): AssetExtension
    {
        if ($this->assetExtension) {
            return $this->assetExtension;
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->assetExtension = $this->twig->getExtension(AssetExtension::class);
    }

    /**
     * Returns the http twig extension.
     *
     * @return HttpFoundationExtension
     */
    private function getHttpExtension(): HttpFoundationExtension
    {
        if ($this->httpExtension) {
            return $this->httpExtension;
        }

        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $this->httpExtension = $this->twig->getExtension(HttpFoundationExtension::class);
    }
}
