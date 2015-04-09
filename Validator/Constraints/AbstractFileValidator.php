<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class AbstractFileValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractFileValidator extends ConstraintValidator
{
	/**
	 * {@inheritdoc}
	 */
    public function validate($file, Constraint $constraint)
    {
    	if (! $file instanceof UploadableInterface) {
    	    throw new UnexpectedTypeException($file, 'Ekyna\Bundle\CoreBundle\Model\UploadableInterface');
    	}
    	if (! $constraint instanceof AbstractFile) {
    	    throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\AbstractFile');
    	}

		/**
		 * @var AbstractFile  $constraint
		 * @var UploadableInterface $file
		 */
    	if ($file->hasFile() || $file->hasKey()) {
    	    if (! $file->hasRename()) {
    	        $this->context->addViolationAt(
    	            'name',
    	            $constraint->nameIsMandatory
    	        );
    	    }
    	} elseif (! $file->hasPath()) {
    	    if ($file->hasRename()) {
    	        $this->context->addViolationAt(
    	            'name',
    	            $constraint->leaveBlank
    	        );
    	    }
    	}
    }
}
