<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateRange
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 * TODO see http://eonasdan.github.io/bootstrap-datetimepicker/#linked-pickers
 */
class DateRange extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $format = $options['time'] ? 'dd/MM/yyyy HH:mm' : 'dd/MM/yyyy';

        $builder
            ->add('startDate', DateTimeType::class, [
                'label'  => 'ekyna_core.field.start_date',
                'format' => $format,
            ])
            ->add('endDate', DateTimeType::class, [
                'label'  => 'ekyna_core.field.end_date',
                'format' => $format,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'time'         => true,
                'inherit_data' => true,
            ]);
    }
}
