<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Ekyna\Bundle\CoreBundle\Model\ImageInterface;

/**
 * AbstractImageValidator
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractImageValidator extends ConstraintValidator
{
    public function validate($image, Constraint $constraint)
    {
    	if (! $image instanceof ImageInterface) {
    	    throw new \InvalidArgumentException('Expected instance of Ekyna\Bundle\CoreBundle\Model\ImageInterface');
    	}
    	
    	if ($image->hasFile()) {
    	    if (! $image->hasName()) {
    	        $this->context->addViolationAt(
    	            'name',
    	            $constraint->nameIsMandatory,
    	            array('%name%' => $image->getFile()->getFilename())
    	        );
    	    }
    	} elseif (! $image->hasPath()) {
    	    if ($image->hasName()) {
    	        $this->context->addViolationAt(
    	            'name',
    	            $constraint->leaveBlank,
    	            array('%name%' => $image->getName())
    	        );
    	    }
    	    if (0 < strlen($image->getAlt())) {
    	        $this->context->addViolation(
    	            'alt',
    	            $constraint->leaveBlank,
    	            array('%alt%' => $image->getAlt())
    	        );
    	    }
    	}
    }
}
