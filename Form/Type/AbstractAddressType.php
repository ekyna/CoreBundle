<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class AbstractAddressType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractAddressType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', 'text', array(
                'label' => 'ekyna_core.field.street',
            ))
            ->add('supplement', 'text', array(
                'label' => 'ekyna_core.field.supplement',
                'required' => false
            ))
            ->add('postalCode', 'text', array(
                'label' => 'ekyna_core.field.postal_code',
            ))
            ->add('city', 'text', array(
                'label' => 'ekyna_core.field.city',
            ))
        ;
    }
}
