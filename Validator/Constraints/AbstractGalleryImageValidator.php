<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Ekyna\Bundle\CoreBundle\Model\ImageGalleryInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class AbstractGalleryImageValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractGalleryImageValidator extends ConstraintValidator
{
    public function validate($image, Constraint $constraint)
    {
        if (! $image instanceof ImageGalleryInterface) {
            throw new \InvalidArgumentException('Expected instance of Ekyna\Bundle\CoreBundle\Model\ImageGalleryInterface');
        }

        /** @var ImageGalleryInterface $image */
        if (!$image->hasFile() && !$image->hasPath()) {
            $this->context->addViolationAt(
                'file',
                $constraint->fileIsMandatory
            );
        }
    }
}
