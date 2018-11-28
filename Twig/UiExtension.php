<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Ekyna\Bundle\CoreBundle\Service\Ui\UiRenderer;
use Ekyna\Component\Resource\Locale\LocaleProviderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Intl\Intl;

/**
 * Class UiExtension
 * @package Ekyna\Bundle\CoreBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UiExtension extends \Twig_Extension
{
    /**
     * @var UiRenderer
     */
    private $uiRenderer;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;


    /**
     * Constructor.
     *
     * @param UiRenderer              $uiRenderer
     * @param LocaleProviderInterface $localeProvider
     */
    public function __construct(UiRenderer $uiRenderer, LocaleProviderInterface $localeProvider)
    {
        $this->uiRenderer = $uiRenderer;
        $this->localeProvider = $localeProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'ui_content_stylesheets',
                [$this->uiRenderer, 'renderContentStylesheets'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'ui_forms_stylesheets',
                [$this->uiRenderer, 'renderFormsStylesheets'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'ui_fonts_stylesheets',
                [$this->uiRenderer, 'renderFontsStylesheets'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'ui_no_image',
                [$this->uiRenderer, 'renderNoImage'],
                ['is_safe' => ['html'], ]
            ),
            new \Twig_SimpleFunction(
                'ui_link',
                [$this->uiRenderer, 'renderLink'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'ui_button',
                [$this->uiRenderer, 'renderButton'],
                ['is_safe' => ['html']]
            ),
            new \Twig_SimpleFunction(
                'ui_button_dropdown',
                [$this->uiRenderer, 'renderButtonDropdown'],
                ['is_safe' => ['html']]
            ),
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
}
