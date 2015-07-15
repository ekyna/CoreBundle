<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @param RequestStack $requestStack
     * @param array        $config
     */
    public function __construct(RequestStack $requestStack, array $config)
    {
        $this->requestStack = $requestStack;
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
        return array(
            new \Twig_SimpleFunction('ui_form_footer', array($this, 'renderFormFooter'), array('is_safe' => array('html'))),
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
     * Renders the form footer.
     *
     * @param FormView $form
     * @return string
     */
    public function renderFormFooter(FormView $form)
    {
        if (array_key_exists('footer', $form->vars)) {
            return $this->controlsTemplate->renderBlock('form_footer', $form->vars['footer']);
        }
        return '';
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
     * @return string
     */
    public function renderLocaleSwitcher()
    {
        // TODO Check if this is a (esi) sub request, as this must never be used in a esi fragment.
        if (null === $request = $this->requestStack->getCurrentRequest()) {
            return '';
        }

        return $this->controlsTemplate->renderBlock('locale_switcher', array(
            'locales' => $this->config['locales'],
            'request' => $request,
        ));
    }

    /**
     * Display the language for the given locale.
     *
     * @return string
     */
    public function getLanguage($locale)
    {
        $inLocale = 'en';
        if (null !== $request = $this->requestStack->getCurrentRequest()) {
            $inLocale = $request->attributes->get('_locale');
        }
        return \Locale::getDisplayLanguage($locale, $inLocale);
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
