<?php

namespace Ekyna\Bundle\CoreBundle\Model;

/**
 * Class AbstractConstants
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractConstants implements ConstantsInterface
{
    /**
     * Returns the constants.
     *
     * @return array
     */
    public static function getConstants()
    {
        return array_keys(static::getConfig());
    }

    /**
     * Returns the constant choices.
     *
     * @return array
     */
    public static function getChoices()
    {
        $choices = [];
        foreach (static::getConfig() as $constant => $config) {
            $choices[$constant] = $config[0];
        }
        return $choices;
    }

    /**
     * Returns the label for the given constant.
     *
     * @param mixed $constant
     * @return string
     */
    public static function getLabel($constant)
    {
        if (null === $constant) {
            return 'ekyna_core.value.undefined';
        }

        static::isValid($constant, true);

        return static::getConfig()[$constant][0];
    }

    /**
     * Returns whether the constant is valid or not.
     *
     * @param mixed   $constant
     * @param boolean $throwException
     *
     * @return bool
     */
    public static function isValid($constant, $throwException = false)
    {
        if (array_key_exists($constant, static::getConfig())) {
            return true;
        }

        if ($throwException) {
            throw new \InvalidArgumentException(sprintf('Unknown constant "%s"', $constant));
        }

        return false;
    }
}
