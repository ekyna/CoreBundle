<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Ekyna\Bundle\CoreBundle\Model\ImageInterface;

/**
 * Class AbstractImageValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractImageValidator extends ConstraintValidator
{
	/**
	 * {@inheritdoc}
	 */
    public function validate($image, Constraint $constraint)
    {
    	if (! $image instanceof ImageInterface) {
    	    throw new \InvalidArgumentException('Expected instance of Ekyna\Bundle\CoreBundle\Model\ImageInterface');
    	}

		/**
		 * @var AbstractUploadable $constraint
		 * @var ImageInterface $image
		 */
    	if (!$image->hasFile() && !$image->hasPath()) {
    	    if (0 < strlen($image->getAlt())) {
    	        $this->context->addViolationAt(
    	            'alt',
    	            $constraint->leaveBlank
    	        );
    	    }
    	}
    }
}
