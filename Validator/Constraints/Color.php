<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Color
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Color extends Constraint
{
    public $invalidCode = 'ekyna_core.color.invalid_code';
    public $unknownFormat = 'ekyna_core.color.unknown_format';
}