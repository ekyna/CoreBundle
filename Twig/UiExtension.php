<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Ekyna\Bundle\CoreBundle\Service\Ui\UiRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class UiExtension
 * @package Ekyna\Bundle\CoreBundle\Twig
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UiExtension extends AbstractExtension
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
        $this->uiRenderer   = $uiRenderer;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'ui_content_stylesheets',
                [$this->uiRenderer, 'renderContentStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_forms_stylesheets',
                [$this->uiRenderer, 'renderFormsStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_fonts_stylesheets',
                [$this->uiRenderer, 'renderFontsStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_no_image',
                [$this->uiRenderer, 'renderNoImage'],
                ['is_safe' => ['html'],]
            ),
            new TwigFunction(
                'ui_link',
                [$this->uiRenderer, 'renderLink'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_button',
                [$this->uiRenderer, 'renderButton'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_dropdown',
                [$this->uiRenderer, 'renderDropdown'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_fa_icon',
                [$this->uiRenderer, 'renderFaIcon'],
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
            new TwigFilter(
                'language',
                [$this, 'getLanguage']
            ),
            new TwigFilter(
                'country',
                [$this, 'getCountry']
            ),
            new TwigFilter(
                'currency_name',
                [$this, 'getCurrencyName']
            ),
            new TwigFilter(
                'currency_symbol',
                [$this, 'getCurrencySymbol']
            ),
            new TwigFilter(
                'ui_fa_icon',
                [$this->uiRenderer, 'renderFaIcon'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTests()
    {
        return [
            new TwigTest('form', function ($var) {
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
