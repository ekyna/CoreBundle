<?php

namespace Ekyna\Bundle\CoreBundle\Validator;

use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class ImageConstraintValidator extends ConstraintValidator
{
    public function validate(ImageInterface $image, Constraint $constraint)
    {
    	if ($image->hasFile() || $image->hasPath()) {
    	    if (0 === strlen($image->getAlt())) {
    	        
    	    }
    	}
    }
}
