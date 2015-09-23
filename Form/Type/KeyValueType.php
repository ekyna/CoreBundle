<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\ChoiceList\SimpleChoiceList;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            $builder->add('key', 'text', [
                'label' => 'ekyna_core.field.key',
            ]);
        } else {
            $builder->add('key', 'choice', [
                'label' => 'ekyna_core.field.key',
                'choice_list' => new SimpleChoiceList($options['allowed_keys'])
            ]);
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'value_options' => [
                    'label' => 'ekyna_core.field.value',
                ],
                'allowed_keys' => null
            ])
            ->setRequired(['value_type'])
            ->setAllowedTypes('allowed_keys', ['null', 'array'])
        ;
    }
}
