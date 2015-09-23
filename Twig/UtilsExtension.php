<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Doctrine\Common\Inflector\Inflector;
use Ekyna\Bundle\CoreBundle\Util\TruncateHtml;
use Symfony\Component\PropertyAccess\Exception\NoSuchIndexException;
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
        return [
            new \Twig_SimpleFunction('get_property', [$this, 'getProperty']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('truncate_html', [$this, 'truncateHtml']),
            new \Twig_SimpleFilter('pluralize', [$this, 'pluralize']),
        ];
    }

    /**
     * Uses PropertyAccess component to return the object property value.
     *
     * @param mixed  $object
     * @param string $propertyPath
     * @param bool   $required
     *
     * @return mixed
     */
    public function getProperty($object, $propertyPath, $required = true)
    {
        if (!$required) {
            try {
                return $this->accessor->getValue($object, $propertyPath);
            } catch(NoSuchIndexException $e) {
                return null;
            }
        }
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
     * Pluralize the given string.
     *
     * @param $string
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
