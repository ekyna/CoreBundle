<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Ekyna\Bundle\CoreBundle\Util\TruncateHtml;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * UtilsExtension
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UtilsExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $accessor;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'get_property' => new \Twig_Function_Method($this, 'getProperty'),
        );
    }

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
     * Uses PropertyAccess component to return the object property value.
     *
     * @param $object
     * @param $propertyPath
     *
     * @return mixed
     */
    public function getProperty($object, $propertyPath)
    {
        return $this->accessor->getValue($object, $propertyPath);
    }

    /**
     * Returns a truncated html string.
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
    	return 'ekyna_core_utils';
    }
}
