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
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @inheritDoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction(
                'ui_assets_base_url',
                [UiRenderer::class, 'renderAssetsBaseUrl'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_content_stylesheets',
                [UiRenderer::class, 'renderContentStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_forms_stylesheets',
                [UiRenderer::class, 'renderFormsStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_fonts_stylesheets',
                [UiRenderer::class, 'renderFontsStylesheets'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_no_image',
                [UiRenderer::class, 'renderNoImage'],
                ['is_safe' => ['html'],]
            ),
            new TwigFunction(
                'ui_link',
                [UiRenderer::class, 'renderLink'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_button',
                [UiRenderer::class, 'renderButton'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_dropdown',
                [UiRenderer::class, 'renderDropdown'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'ui_fa_icon',
                [UiRenderer::class, 'renderFaIcon'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @inheritDoc
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
                [UiRenderer::class, 'renderFaIcon'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @inheritDoc
     */
    public function getTests()
    {
        return [
            new TwigTest(
                'form',
                function ($var) {
                    return $var instanceof FormView;
                }
            ),
        ];
    }

    /**
     * Returns the language for the given locale.
     *
     * @param string      $locale
     * @param string|null $displayLocale
     *
     * @return string
     */
    public function getLanguage(string $locale, string $displayLocale = null): string
    {
        return \Locale::getDisplayLanguage($locale, $displayLocale ?? $this->getInLocale());
    }

    /**
     * Returns the current locale.
     *
     * @return string
     */
    private function getInLocale(): string
    {
        if ($this->inLocale) {
            return $this->inLocale;
        }

        if ($request = $this->requestStack->getMasterRequest()) {
            return $this->inLocale = $request->getLocale();
        }

        return $this->inLocale = \Locale::getDefault();
    }

    /**
     * Returns the country name for the given code.
     *
     * @param string      $code
     * @param string|null $displayLocale
     *
     * @return string
     */
    public function getCountry(string $code, string $displayLocale = null): string
    {
        return Intl::getRegionBundle()->getCountryName($code, $displayLocale ?? $this->getInLocale());
    }

    /**
     * Returns the currency name for the given code.
     *
     * @param string      $code
     * @param string|null $displayLocale
     *
     * @return string
     */
    public function getCurrencyName(string $code, string $displayLocale = null): string
    {
        return Intl::getCurrencyBundle()->getCurrencyName($code, $displayLocale ?? $this->getInLocale());
    }

    /**
     * Returns the currency symbol for the given code.
     *
     * @param string      $code
     * @param string|null $displayLocale
     *
     * @return string
     */
    public function getCurrencySymbol(string $code, string $displayLocale = null): string
    {
        return Intl::getCurrencyBundle()->getCurrencySymbol($code, $displayLocale ?? $this->getInLocale());
    }
}
