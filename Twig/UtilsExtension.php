<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Doctrine\Common\Inflector\Inflector;
use Ekyna\Bundle\CoreBundle\Util\Truncator;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * UtilsExtension
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UtilsExtension extends AbstractExtension
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var string[]
     */
    private $localeStack = [];


    /**
     * Constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritdoc
     */
    public function getFilters()
    {
        return [
            new TwigFilter('truncate_html', [$this, 'truncateHtml'], ['is_safe' => ['html']]),
            new TwigFilter('pluralize', [$this, 'pluralize']),
            new TwigFilter('base64_inline_file', [$this, 'base64InlineFile']),
            new TwigFilter('base64_inline_data', [$this, 'base64InlineData']),
            new TwigFilter('unset', [$this, 'unset']),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('unset', [$this, 'unset']),
            new TwigFunction('trans_set_locale', [$this, 'translatorSetLocale']),
            new TwigFunction('trans_revert_locale', [$this, 'translatorRevertLocale']),
        ];
    }

    /**
     * Returns a truncated html string.
     *
     * @param string $html
     * @param int    $limit
     * @param string $endChar
     *
     * @return string
     */
    public function truncateHtml(string $html = null, int $limit = 128, string $endChar = '&hellip;')
    {
        if (empty($html)) {
            return '';
        }

        return (new Truncator($html))->truncate($limit, $endChar);
    }

    /**
     * Pluralize the given string.
     *
     * @param $string
     *
     * @return string
     */
    public function pluralize(string $string = null)
    {
        if (empty($string)) {
            return '';
        }

        return Inflector::pluralize($string);
    }

    /**
     * Encodes and inlines the given file path.
     *
     * @param string $path
     * @param string $mimeType
     * @param array  $parameters
     *
     * @return string
     */
    public function base64InlineFile(string $path, string $mimeType, array $parameters = [])
    {
        if (file_exists($path)) {
            return $this->base64InlineData(file_get_contents($path), $mimeType, $parameters);
        }

        return null;
    }

    /**
     * Encodes and inlines the given binary data.
     *
     * @param string|resource $data
     * @param string          $mimeType
     * @param array           $parameters
     *
     * @return string
     */
    public function base64InlineData($data, string $mimeType, array $parameters = [])
    {
        $output = 'data:' . $mimeType;
        foreach ($parameters as $name => $value) {
            $output .= ';' . $name . '=' . $value;
        }

        if (is_resource($data)) {
            $data = stream_get_contents($data);
        }

        return $output . ';base64,' . base64_encode($data);
    }

    /**
     * Unsets the given array's key.
     *
     * @param array  $array
     * @param string $key
     */
    public function unset(array $array, string $key)
    {
        unset($array[$key]);
    }

    /**
     * Sets the translator locator.
     *
     * @param string $locale
     */
    public function translatorSetLocale(string $locale)
    {
        array_push($this->localeStack, $this->translator->getLocale());

        $this->translator->setLocale(strtolower($locale));
    }

    /**
     * Reverts the translator locale.
     */
    public function translatorRevertLocale()
    {
        if (null === $locale = array_pop($this->localeStack)) {
            return;
        }

        $this->translator->setLocale($locale);
    }
}
