<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

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
    	    throw new UnexpectedTypeException($image, 'Ekyna\Bundle\CoreBundle\Model\ImageInterface');
    	}
    	if (! $constraint instanceof AbstractImage) {
    	    throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\AbstractImage');
    	}

		/**
		 * @var AbstractImage $constraint
		 * @var ImageInterface $image
		 */
    	if (!($image->hasFile() || $image->hasKey() || !$image->hasPath())) {
    	    if (0 < strlen($image->getAlt())) {
    	        $this->context->addViolationAt(
    	            'alt',
    	            $constraint->leaveBlank
    	        );
    	    }
    	}
    }
}
