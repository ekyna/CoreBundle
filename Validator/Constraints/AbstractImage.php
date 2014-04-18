<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * AbstractImage
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractImage extends Constraint
{
    public $nameIsMandatory = 'ekyna_core.image.validation.name_is_mandatory';
    public $leaveBlank = 'ekyna_core.image.validation.leave_blank';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
