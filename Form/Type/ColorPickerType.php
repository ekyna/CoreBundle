<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ColorPickerType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 * @see     http://www.jqueryrain.com/?obvgj1Bz
 */
class ColorPickerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $pickerOptions = $options['pickerOptions'];
        if (0 < strlen($view->vars['value'])) {
            $pickerOptions['value'] = $view->vars['value'];
        }

        // TODO colorSelectors [hex => hex]

        $view->vars = array_replace($view->vars, [
            'pickerOptions' => $pickerOptions,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // @see http://mjolnic.com/bootstrap-colorpicker/
        $resolver->setDefaults([
            'pickerOptions' => [
                'component' => '.input-group-btn',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_color_picker';
    }
}
