<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Uploadable
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Uploadable extends Constraint
{
    public $fileIsMandatory = 'ekyna_core.uploadable.file_is_mandatory';
    public $nameIsMandatory = 'ekyna_core.uploadable.name_is_mandatory';
    public $leaveBlank      = 'ekyna_core.uploadable.leave_blank';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
