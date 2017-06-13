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
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_core_utils';
    }
}
