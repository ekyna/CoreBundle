<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class AbstractGalleryImage
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractGalleryImage extends Constraint
{
    public $fileIsMandatory = 'ekyna_core.image.file_is_mandatory';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}