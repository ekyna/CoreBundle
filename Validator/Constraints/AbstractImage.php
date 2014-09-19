<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class AbstractImage
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractImage extends Constraint
{
    public $nameIsMandatory = 'ekyna_core.image.name_is_mandatory';
    public $leaveBlank = 'ekyna_core.image.leave_blank';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
