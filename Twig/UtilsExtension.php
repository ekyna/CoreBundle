<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Doctrine\Common\Inflector\Inflector;
use Ekyna\Bundle\CoreBundle\Util\Truncator;

/**
 * UtilsExtension
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UtilsExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('truncate_html', [$this, 'truncateHtml'], ['is_safe' => ['html']]),
            new \Twig_SimpleFilter('pluralize', [$this, 'pluralize']),
            new \Twig_SimpleFilter('base64_inline_file', [$this, 'base64InlineFile']),
            new \Twig_SimpleFilter('base64_inline_data', [$this, 'base64InlineData']),
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
    public function truncateHtml($html, $limit, $endChar = '&hellip;')
    {
        return (new Truncator($html))->truncate($limit, $endChar);
    }

    /**
     * Pluralize the given string.
     *
     * @param $string
     *
     * @return string
     */
    public function pluralize($string)
    {
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
    public function base64InlineFile($path, $mimeType, array $parameters = [])
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
     * @param string $mimeType
     * @param array  $parameters
     *
     * @return string
     */
    public function base64InlineData($data, $mimeType, array $parameters = [])
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
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_core_utils';
    }
}
