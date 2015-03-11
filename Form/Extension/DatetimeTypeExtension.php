<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Ekyna\Bundle\CoreBundle\Form\Util\MomentFormatConverter;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DatetimeTypeExtension
 * @package Ekyna\Bundle\CoreBundle\Form\Extension
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DatetimeTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $pickerOptions = $options['picker_options'];

        // Set automatically the language
        if (!isset($options['picker_options']['locale'])) {
            $pickerOptions['locale'] = \Locale::getDefault();
        }
        if ($pickerOptions['locale'] == 'en') {
            unset($pickerOptions['locale']);
        }

        // Convert format for moment.js
        $pickerOptions['format'] = MomentFormatConverter::convert($options['format']);

        $view->vars = array_replace($view->vars, array(
            'picker_options' => $pickerOptions,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy HH:mm', // TODO localised configurable format
                'picker_options' => array(
                    'widgetPositioning' => array('horizontal' => 'right'),
                    'showTodayButton' => true,
                    'showClear'       => true,
                    'showClose'       => true,
                ),
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'datetime';
    }
}
