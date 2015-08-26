<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class UploadableValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UploadableValidator extends ConstraintValidator
{
    /**
	 * {@inheritdoc}
	 */
    public function validate($uploadable, Constraint $constraint)
    {
    	if (! $uploadable instanceof UploadableInterface) {
    	    throw new UnexpectedTypeException($uploadable, 'Ekyna\Bundle\CoreBundle\Model\UploadableInterface');
    	}
    	if (! $constraint instanceof Uploadable) {
    	    throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Uploadable');
    	}

		/**
		 * @var Uploadable          $constraint
		 * @var UploadableInterface $uploadable
		 */
    	if ($uploadable->hasFile() || $uploadable->hasKey()) {
    	    if (! $uploadable->hasRename()) {
    	        $this->context->addViolationAt(
    	            'rename',
    	            $constraint->nameIsMandatory
    	        );
    	    }
    	} elseif (! $uploadable->hasPath()) {
            $this->context->addViolationAt(
                'file',
                $constraint->fileIsMandatory
            );
    	    if ($uploadable->hasRename()) {
    	        $this->context->addViolationAt(
    	            'rename',
    	            $constraint->leaveBlank
    	        );
    	    }
    	}
    }
}
