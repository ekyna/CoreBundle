<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Ekyna\Bundle\CoreBundle\Model\FileInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

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
    	if (! $file instanceof FileInterface) {
    	    throw new \InvalidArgumentException('Expected instance of Ekyna\Bundle\CoreBundle\Model\FileInterface');
    	}

		/**
		 * @var AbstractFile  $constraint
		 * @var FileInterface $file
		 */
    	if ($file->hasFile()) {
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
