<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class ColorValidator
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ColorValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($code, Constraint $constraint)
    {
        if (! $constraint instanceof Color) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\Color');
        }

        if (null === $code) {
            return;
        }

        /** @var Color $constraint */

        $types = [
            'RGBA' => ['rgba', '\([0-9]{1,3},\s?[0-9]{1,3},\s?[0-9]{1,3},\s?[0-9\.]{1,5}\)'],
            'RGB'  => ['rgb',  '\([0-9]{1,3},\s?[0-9]{1,3},\s?[0-9]{1,3}\)'],
            'HSLA' => ['hsla', '\([0-9]{1,3},\s?[0-9]{1,3},\s?[0-9]{1,3},\s?[0-9\.]{1,5}\)'],
            'HSL'  => ['hsl',  '\([0-9]{1,3},\s?[0-9]{1,3},\s?[0-9]{1,3}\)'],
            'HSV'  => ['hsv',  '\([0-9]{1,3},\s?[0-9]{1,3},\s?[0-9]{1,3}\)'],
            'HEX'  => ['#',    '([0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})'],
        ];

        foreach($types as $type => $tests) {
            if ($tests[0] !== substr($code, 0, strlen($tests[0]))) {
                continue;
            }
            $regex = sprintf('@^(%s%s)$@', $tests[0], $tests[1]);
            if (!preg_match($regex, $code, $matches)) {
                $this->context->addViolation($constraint->invalidCode, ['%type%' => $type, '%code%' => $code]);
            }
            return;
        }

        $this->context->addViolation($constraint->unknownFormat, ['%color%' => $code]);
    }
}
