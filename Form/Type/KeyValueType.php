<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class KeyValueType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Bart van den Burg <bart@burgov.nl>
 * @see https://github.com/Burgov/KeyValueFormBundle/blob/master/Form/Type/KeyValueRowType.php
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class KeyValueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null === $options['allowed_keys']) {
            $builder->add('key', 'text', array(
                'label' => 'ekyna_core.field.key',
            ));
        } else {
            $builder->add('key', 'choice', array(
                'label' => 'ekyna_core.field.key',
                'choice_list' => new SimpleChoiceList($options['allowed_keys'])
            ));
        }
        $builder->add('value', $options['value_type'], $options['value_options']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_key_value';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'value_options' => array(
                    'label' => 'ekyna_core.field.value',
                ),
                'allowed_keys' => null
            ))
            ->setRequired(array('value_type'))
            ->setAllowedTypes(array(
                'allowed_keys' => array('null', 'array')
            ))
        ;
    }
}
