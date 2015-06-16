<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Ekyna\Bundle\CoreBundle\Form\DataTransformer\HashToKeyValueArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

/**
 * Class KeyValueCollectionType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Bart van den Burg <bart@burgov.nl>
 * @see https://github.com/Burgov/KeyValueFormBundle/blob/master/Form/Type/KeyValueType.php
 * @author Étienne Dauvergne <contact@ekyna.com>
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
                $output = array();
                foreach ($input as $key => $value) {
                    $output[] = array(
                        'key' => $key,
                        'value' => $value
                    );
                }
                $e->setData($output);
            }, 1
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'type'                 => 'ekyna_key_value',
                'value_type'           => 'text',
                'value_options'        => array(),
                'allowed_keys'         => null,
                'use_container_object' => false,
                'options'              => function (Options $options) {
                    return array(
                        'value_type'    => $options['value_type'],
                        'value_options' => $options['value_options'],
                        'allowed_keys'  => $options['allowed_keys']
                    );
                },
            ))
            ->setRequired(array('value_type'))
            ->setAllowedTypes(array(
                'allowed_keys' => array('null', 'array'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'ekyna_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_key_value_collection';
    }
}