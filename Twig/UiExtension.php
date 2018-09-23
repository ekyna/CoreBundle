<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Ekyna\Component\Resource\Locale\LocaleProviderInterface;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UiExtension
 * @package Ekyna\Bundle\CoreBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UiExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * @var AssetExtension
     */
    private $assetExtension;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    /**
     * @var array
     */
    private $config;

    /**
     * @var \Twig_Template
     */
    private $controlsTemplate;

    /**
     * @var OptionsResolver
     */
    private $buttonOptionsResolver;


    /**
     * Constructor.
     *
     * @param AssetExtension          $assetExtension
     * @param LocaleProviderInterface $localeProvider
     * @param array                   $config
     */
    public function __construct(
        AssetExtension $assetExtension,
        LocaleProviderInterface $localeProvider,
        array $config
    ) {
        $this->assetExtension = $assetExtension;
        $this->localeProvider = $localeProvider;
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $twig)
    {
        $this->controlsTemplate = $twig->loadTemplate($this->config['controls_template']);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('ui_content_stylesheets', [$this, 'renderContentStylesheets'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_forms_stylesheets', [$this, 'renderFormsStylesheets'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_fonts_stylesheets', [$this, 'renderFontsStylesheets'], ['is_safe' => ['html']]),

            new \Twig_SimpleFunction('ui_no_image', [$this, 'renderNoImage'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_link', [$this, 'renderLink'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_button', [$this, 'renderButton'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_button_dropdown', [$this, 'renderButtonDropdown'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('ui_google_font', [$this, 'renderGoogleFontLink'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('language', [$this, 'getLanguage']),
            new \Twig_SimpleFilter('country', [$this, 'getCountry']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new \Twig_SimpleTest('form', function ($var) {
                return $var instanceof FormView;
            }),
        ];
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
        return $this->controlsTemplate->renderBlock('no_image', [
            'no_image_path' => $this->config['no_image_path'],
            'attr'          => $attributes,
        ]);
    }

    /**
     * Renders the link.
     *
     * @param        $href
     * @param string $label
     * @param array  $options
     * @param array  $attributes
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
     * @param string $label
     * @param array  $options
     * @param array  $attributes
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function renderButton($label = '', array $options = [], array $attributes = [])
    {
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

        return $this->controlsTemplate->renderBlock('button', [
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

        return $this->controlsTemplate->renderBlock('button_dropdown', [
            'label'   => $label,
            'theme'   => $theme,
            'size'    => $size,
            'actions' => $actions,
            'right'   => $right,
        ]);
    }

    /**
     * Display the language for the given locale.
     *
     * @param string $locale
     *
     * @return string
     */
    public function getLanguage($locale)
    {
        return \Locale::getDisplayLanguage($locale, $this->localeProvider->getCurrentLocale());
    }

    /**
     * Display the country name for the given code.
     *
     * @param string $countryCode
     *
     * @return string
     */
    public function getCountry($countryCode)
    {
        return Intl::getRegionBundle()->getCountryName($countryCode, $this->localeProvider->getCurrentLocale());
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
     * Builds a stylesheet tag.
     *
     * @param $path
     *
     * @return string
     */
    private function buildStylesheetTag($path)
    {
        return '<link href="' . $this->assetExtension->getAssetUrl($path) . '" rel="stylesheet" type="text/css">' . "\n";
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_core_ui';
    }
}
