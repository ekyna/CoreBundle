<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\ExceptionInterface;

/**
 * Class ConfigurationValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ConfigurationValidator extends ConstraintValidator
{
    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var Configuration $constraint */

        $processor = new Processor();

        try {
            $processor->process($constraint->definition, [$constraint->root => $value]);
        } catch (ExceptionInterface $e) {
            $this
                ->context
                ->buildViolation($e->getMessage())
                ->addViolation();
        }
    }
}
