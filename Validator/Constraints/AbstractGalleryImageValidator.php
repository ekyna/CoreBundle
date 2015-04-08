<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Ekyna\Bundle\CoreBundle\Model\GalleryImageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class AbstractGalleryImageValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractGalleryImageValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($galleryImage, Constraint $constraint)
    {
        if (! $galleryImage instanceof GalleryImageInterface) {
            throw new UnexpectedTypeException($galleryImage, 'Ekyna\Bundle\CoreBundle\Model\GalleryImageInterface');
        }
        if (! $constraint instanceof AbstractGalleryImage) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\AbstractGalleryImage');
        }

        /**
         * @var AbstractGalleryImage $constraint
         * @var GalleryImageInterface $galleryImage
         */
        if (!($galleryImage->hasFile() || $galleryImage->hasKey() || $galleryImage->hasPath())) {
            $this->context->addViolationAt(
                'file',
                $constraint->fileIsMandatory
            );
        }
    }
}
