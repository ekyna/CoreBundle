<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Ekyna\Bundle\CoreBundle\Form\DataTransformer\HashToKeyValueArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

/**
 * Class KeyValueCollectionType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Bart van den Burg <bart@burgov.nl>
 * @see     https://github.com/Burgov/KeyValueFormBundle/blob/master/Form/Type/KeyValueType.php
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class KeyValueCollectionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new HashToKeyValueArrayTransformer($options['use_container_object']));

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $e) {
                $input = $e->getData();
                if (null === $input) {
                    return;
                }
                $output = [];
                foreach ($input as $key => $value) {
                    $output[] = [
                        'key'   => $key,
                        'value' => $value,
                    ];
                }
                $e->setData($output);
            }, 1
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'entry_type'           => KeyValueType::class,
                'entry_options'        => function (Options $options) {
                    return [
                        'value_type'    => $options['value_type'],
                        'value_options' => $options['value_options'],
                        'allowed_keys'  => $options['allowed_keys'],
                    ];
                },
                'value_type'           => TextType::class,
                'value_options'        => [],
                'allowed_keys'         => null,
                'use_container_object' => false,
            ])
            ->setRequired(['value_type'])
            ->setAllowedTypes('allowed_keys', ['null', 'array']);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return CollectionType::class;
    }
}
