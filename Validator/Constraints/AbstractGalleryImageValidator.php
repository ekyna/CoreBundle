<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Ekyna\Bundle\CoreBundle\Model\GalleryImageInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

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
            throw new \InvalidArgumentException('Expected instance of Ekyna\Bundle\CoreBundle\Model\ImageGalleryInterface');
        }

        /**
         * @var AbstractGalleryImage $constraint
         * @var GalleryImageInterface $galleryImage
         */
        if (!$galleryImage->hasFile() && !$galleryImage->hasPath()) {
            $this->context->addViolationAt(
                'file',
                $constraint->fileIsMandatory
            );
        }
    }
}
