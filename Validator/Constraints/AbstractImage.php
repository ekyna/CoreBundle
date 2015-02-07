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
    public $leaveBlank = 'ekyna_core.file.leave_blank';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
