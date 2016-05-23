<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class KeyValueType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Bart van den Burg <bart@burgov.nl>
 * @see     https://github.com/Burgov/KeyValueFormBundle/blob/master/Form/Type/KeyValueRowType.php
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class KeyValueType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (null === $options['allowed_keys']) {
            $builder->add('key', TextType::class, [
                'label' => 'ekyna_core.field.key',
            ]);
        } else {
            $builder->add('key', ChoiceType::class, [
                'label'            => 'ekyna_core.field.key',
                'choice_list'      => $options['allowed_keys'],
                'choices_as_value' => true,
            ]);
        }
        $builder->add('value', $options['value_type'], $options['value_options']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $valueOptionsDefaults = [
            'label' => 'ekyna_core.field.value',
        ];

        $resolver
            ->setDefaults([
                'value_type'    => TextType::class,
                'value_options' => $valueOptionsDefaults,
                'allowed_keys'  => null,
            ])
            ->setAllowedTypes('value_type', 'string')
            ->setAllowedTypes('value_options', 'array')
            ->setAllowedTypes('allowed_keys', ['null', 'array'])
            ->setNormalizer('value_options', function (Options $options, $value) use ($valueOptionsDefaults) {
                return array_replace($valueOptionsDefaults, $value);
            });
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_key_value';
    }
}
