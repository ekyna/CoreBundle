<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Interface ConstantsInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ConstantsInterface
{
    /**
     * Returns the constants configuration.
     *
     * Implements this method who must return an array with constants as keys,
     * and configuration arrays as values. The first value of configuration
     * arrays must be the label.<br><br>
     * Example:
     * <code>
     * return array(
     *     self::CONSTANT_1 => array("Constant 1 label", "Constant 1 custom value"),
     *     self::CONSTANT_2 => array("Constant 2 label", "Constant 2 custom value"),
     * );
     * </code>
     * @return array
     */
    public static function getConfig();
}
