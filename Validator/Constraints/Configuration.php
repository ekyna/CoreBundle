<?php

namespace Ekyna\Bundle\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Configuration
 * @package Ekyna\Bundle\CoreBundle\Validator\Constraints
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Configuration extends Constraint
{
    /**
     * @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    public $definition;


    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return 'definition';
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return array('definition');
    }
}
