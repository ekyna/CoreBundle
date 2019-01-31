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
 * @see     https://itsjavi.com/bootstrap-colorpicker/
 */
class ColorPickerType extends AbstractType
{
    /**
     * @var array
     */
    private $colorsMap = [];


    /**
     * Constructor.
     *
     * @param array $colors
     */
    public function __construct(array $colors = [])
    {
        foreach ($colors as $color) {
            $this->colorsMap['#' . $color] = '#' . $color;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $pickerOptions = [
            'component' => '.input-group-btn',
        ];
        if (0 < strlen($view->vars['value'])) {
            $pickerOptions['color'] = $view->vars['value'];
        }

        if (!empty($this->colorsMap)) {
            $pickerOptions['colorSelectors'] = $this->colorsMap;
        }

        if ($options['doubleSize']) {
            $pickerOptions['customClass'] = 'colorpicker-2x';
            $pickerOptions['sliders'] = [
                'saturation' => [
                    'maxLeft' => 200,
                    'maxTop'  => 200,
                ],
                'hue'        => [
                    'maxTop' => 200,
                ],
                'alpha'      => [
                    'maxTop' => 200,
                ],
            ];
        }

        $view->vars = array_replace($view->vars, [
            'pickerOptions' => $pickerOptions,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'doubleSize' => true,
            ])
            ->addAllowedTypes('doubleSize', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_color_picker';
    }
}
