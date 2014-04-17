<?php

namespace Ekyna\Bundle\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * ImageConstraint
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImageConstraint extends Constraint
{
    public $message = 'La chaîne "%string%" contient un caractère non autorisé : elle ne peut contenir que des lettres et des chiffres.';
}
