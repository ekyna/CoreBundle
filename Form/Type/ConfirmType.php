<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Braincrafted\Bundle\BootstrapBundle\Form\Type\FormActionsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;

/**
 * Class ConfirmType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ConfirmType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $buttons = [
            'submit' => [
                'type'    => Type\SubmitType::class,
                'options' => [
                    'button_class' => $options['submit_class'],
                    'label'        => $options['submit_label'],
                    'attr'         => $options['submit_icon'] ? ['icon' => $options['submit_icon']] : [],
                ],
            ],
        ];

        if ($options['cancel_path']) {
            $buttons['cancel'] = [
                'type'    => Type\ButtonType::class,
                'options' => [
                    'label'        => 'ekyna_core.button.cancel',
                    'button_class' => 'default',
                    'as_link'      => true,
                    'attr'         => [
                        'class' => 'form-cancel-btn',
                        'icon'  => 'remove',
                        'href'  => $options['cancel_path'],
                    ],
                ],
            ];
        }

        $builder
            ->add('confirm', Type\CheckboxType::class, [
                'label'       => $options['message'],
                'attr'        => ['align_with_widget' => true],
                'required'    => true,
                'constraints' => [
                    new Constraints\IsTrue(),
                ],
            ])
            ->add('actions', FormActionsType::class, [
                'buttons' => $buttons,
            ]);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'message'      => 'ekyna_core.message.remove_confirm',
                'submit_label' => 'ekyna_core.button.confirm',
                'submit_class' => 'danger',
                'submit_icon'  => 'remove',
                'cancel_path'  => null,
            ])
            ->setAllowedTypes('message', 'string')
            ->setAllowedTypes('submit_label', 'string')
            ->setAllowedTypes('submit_class', 'string')
            ->setAllowedTypes('submit_icon', ['string', 'null'])
            ->setAllowedTypes('cancel_path', ['string', 'null']);
    }
}
