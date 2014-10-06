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
            new \Twig_SimpleFunction('get_property', array($this, 'getProperty')),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('truncate_html', array($this, 'truncateHtml'))
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
     * @param string $html
     * @param int $limit
     * @param string $endChar
     *
     * @return string
     */
    public function truncateHtml($html, $limit, $endChar = '&hellip;')
    {
        $output = new TruncateHtml($html);
        return $output->cut($limit, $endChar);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_core_utils';
    }
}
