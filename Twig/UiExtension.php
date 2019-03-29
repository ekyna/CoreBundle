<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Ekyna\Bundle\CoreBundle\Service\Ui\UiRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var string
     */
    private $inLocale;


    /**
     * Constructor.
     *
     * @param UiRenderer   $uiRenderer
     * @param RequestStack $requestStack
     */
    public function __construct(UiRenderer $uiRenderer, RequestStack $requestStack)
    {
        $this->uiRenderer = $uiRenderer;
        $this->requestStack = $requestStack;
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
                ['is_safe' => ['html'],]
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
            new \Twig_SimpleFilter('currency_name', [$this, 'getCurrencyName']),
            new \Twig_SimpleFilter('currency_symbol', [$this, 'getCurrencySymbol']),
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
     * Returns the language for the given locale.
     *
     * @param string $locale
     * @param string $displayLocale
     *
     * @return string
     */
    public function getLanguage($locale, $displayLocale = null)
    {
        return \Locale::getDisplayLanguage($locale, $displayLocale ?? $this->getInLocale());
    }

    /**
     * Returns the country name for the given code.
     *
     * @param string $code
     * @param string $displayLocale
     *
     * @return string
     */
    public function getCountry($code, $displayLocale = null)
    {
        return Intl::getRegionBundle()->getCountryName($code, $displayLocale ?? $this->getInLocale());
    }

    /**
     * Returns the currency name for the given code.
     *
     * @param string $code
     * @param string $displayLocale
     *
     * @return string
     */
    public function getCurrencyName($code, $displayLocale = null)
    {
        return Intl::getCurrencyBundle()->getCurrencyName($code, $displayLocale ?? $this->getInLocale());
    }

    /**
     * Returns the currency symbol for the given code.
     *
     * @param string $code
     * @param string $displayLocale
     *
     * @return string
     */
    public function getCurrencySymbol($code, $displayLocale = null)
    {
        return Intl::getCurrencyBundle()->getCurrencySymbol($code, $displayLocale ?? $this->getInLocale());
    }

    /**
     * Returns the current locale.
     *
     * @return string
     */
    private function getInLocale()
    {
        if ($this->inLocale) {
            return $this->inLocale;
        }

        if ($request = $this->requestStack->getMasterRequest()) {
            return $this->inLocale = $request->getLocale();
        }

        return $this->inLocale = \Locale::getDefault();
    }
}
