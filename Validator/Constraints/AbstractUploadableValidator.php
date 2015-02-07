<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

/**
 * Class AbstractUploadableValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AbstractUploadableValidator extends ConstraintValidator
{
	/**
	 * {@inheritdoc}
	 */
    public function validate($uploadable, Constraint $constraint)
    {
    	if (! $uploadable instanceof UploadableInterface) {
    	    throw new \InvalidArgumentException('Expected instance of Ekyna\Bundle\CoreBundle\Model\UploadableInterface');
    	}

		/**
		 * @var AbstractUploadable $constraint
		 * @var UploadableInterface $uploadable
		 */
    	if ($uploadable->hasFile()) {
    	    if (! $uploadable->hasRename()) {
    	        $this->context->addViolationAt(
    	            'name',
    	            $constraint->nameIsMandatory
    	        );
    	    }
    	} elseif (! $uploadable->hasPath()) {
    	    if ($uploadable->hasRename()) {
    	        $this->context->addViolationAt(
    	            'name',
    	            $constraint->leaveBlank
    	        );
    	    }
    	}
    }
}
