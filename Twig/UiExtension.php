<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Ekyna\Bundle\CoreBundle\Locale\LocaleProviderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UiExtension
 * @package Ekyna\Bundle\CoreBundle\Twig
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UiExtension extends \Twig_Extension
{
    /**
     * @var RequestStack
     */
    private $requestStack;

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
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $buttonOptionsResolver;


    /**
     * Constructor.
     *
     * @param RequestStack            $requestStack
     * @param LocaleProviderInterface $localeProvider
     * @param array                   $config
     */
    public function __construct(RequestStack $requestStack, LocaleProviderInterface $localeProvider, array $config)
    {
        $this->requestStack   = $requestStack;
        $this->localeProvider = $localeProvider;
        $this->config         = $config;
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
        return array(
            new \Twig_SimpleFunction('ui_no_image', array($this, 'renderNoImage'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ui_link', array($this, 'renderLink'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ui_button', array($this, 'renderButton'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ui_google_font', array($this, 'renderGoogleFontLink'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('ui_locale_switcher', array($this, 'renderLocaleSwitcher'), array('is_safe' => array('html'))),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('language', array($this, 'getLanguage')),
            new \Twig_SimpleFilter('country', array($this, 'getCountry')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return array(
            'locales' => $this->config['locales'],
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getTests ()
    {
        return array(
            new \Twig_SimpleTest('form', function ($var) { return $var instanceof FormView; }),
        );
    }

    /**
     * Renders the "no image" img.
     *
     * @param array $attributes
     * @return string
     */
    public function renderNoImage(array $attributes = array())
    {
        return $this->controlsTemplate->renderBlock('no_image', array(
            'no_image_path' => $this->config['no_image_path'],
            'attr' => $attributes,
        ));
    }

    /**
     * Renders the link.
     *
     * @param $href
     * @param string $label
     * @param array $options
     * @param array $attributes
     * @return string
     */
    public function renderLink($href, $label = '', array $options = array(), array $attributes = array())
    {
        $options['type'] = 'link';
        $options['path'] = $href;

        return $this->renderButton($label, $options, $attributes);
    }

    /**
     * Renders the button.
     *
     * @param string $label
     * @param array $options
     * @param array $attributes
     * @return string
     * @throws \InvalidArgumentException
     */
    public function renderButton($label = '', array $options = array(), array $attributes = array())
    {
        $options = $this->getButtonOptionsResolver()->resolve($options);

        $tag = 'button';
        $classes = array('btn', 'btn-'.$options['theme'], 'btn-'.$options['size']);
        $defaultAttributes = array(
        	'class' => sprintf('btn btn-%s btn-%s', $options['theme'], $options['size']),
        );
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
        if(0 < strlen($options['icon'])) {
            $icon = $options['fa_icon'] ? 'fa fa-'.$options['icon'] : 'glyphicon glyphicon-'.$options['icon'];
        }

        return $this->controlsTemplate->renderBlock('button', array(
            'tag'   => $tag,
            'attr'  => $attributes,
            'label' => $label,
            'icon'  => $icon,
        ));
    }

    /**
     * Renders the google font css link.
     *
     * @return string
     */
    public function renderGoogleFontLink()
    {
        if (0 < strlen($this->config['google_font_url'])) {
            return '<link href="' . $this->config['google_font_url'] . '" rel="stylesheet" type="text/css">' . "\n";
        }
        return '';
    }

    /**
     * Renders the locale switcher.
     *
     * @param array $attributes
     * @return string
     */
    public function renderLocaleSwitcher($attributes = array())
    {
        // TODO Check if this is a (esi) sub request, as this must never be used in a esi fragment.
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return '';
        }

        if (!array_key_exists('class', $attributes)) {
            $attributes['class'] = 'list-inline locale-switcher';
        }

        return $this->controlsTemplate->renderBlock('locale_switcher', array(
            'locales' => $this->config['locales'],
            'request' => $request,
            'attr' => $attributes,
        ));
    }

    /**
     * Display the language for the given locale.
     *
     * @param string $locale
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
            ->setDefaults(array(
                'type'    => 'button',
                'theme'   => 'default',
                'size'    => 'sm',
                'icon'    => null,
                'fa_icon' => false,
                'path'    => null,
            ))
            ->setRequired(array('type', 'theme', 'size'))
            ->setAllowedValues(array(
                'type'  => array('link', 'button', 'submit', 'reset'),
                'theme' => array('default', 'primary', 'success', 'warning', 'danger'),
                'size'  => array('xs', 'sm', 'md', 'lg'),
            ))
            ->setAllowedTypes(array(
                'icon' => array('string', 'null'),
                'fa_icon' => 'bool',
                'path' => array('string', 'null'),
            ))
            ;
        }

        return $this->buttonOptionsResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_core_ui';
    }
}
