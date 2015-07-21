<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DateRange
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 * TODO see http://eonasdan.github.io/bootstrap-datetimepicker/#linked-pickers
 */
class DateRange extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $format = $options['time'] ? 'dd/MM/yyyy HH:mm' : 'dd/MM/yyyy';

        $builder
            ->add('startDate', 'datetime', array(
                'label' => 'ekyna_core.field.start_date',
                'format' => $format,
            ))
            ->add('endDate', 'datetime', array(
                'label' => 'ekyna_core.field.end_date',
                'format' => $format,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'time'         => true,
                'inherit_data' => true,
            ))
        ;
    }

    public function getName()
    {
        return 'ekyna_date_range';
    }
}