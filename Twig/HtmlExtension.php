<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Ekyna\Bundle\CoreBundle\Util\TruncateHtml;

/**
 * HtmlExtension
 *
 * @author Leon Radley <leon@radley.se>
 * @author Étienne Dauvergne <contact@ekyna.com>
 * 
 * @see https://gist.github.com/leon/2857883
 */
class HtmlExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            'truncate_html' => new \Twig_Filter_Method($this, 'truncateHtml')
        );
    }

    /**
     * Returns a truncated html string
     * 
     * @param unknown $html
     * @param unknown $limit
     * @param string $endchar
     * 
     * @return string
     */
    public function truncateHtml($html, $limit, $endchar = '&hellip;')
    {
        $output = new TruncateHtml($html);
        return $output->cut($limit, $endchar);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_core_html';
    }
}
