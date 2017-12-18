<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Ekyna\Bundle\CoreBundle\Form\Util\MomentFormatConverter;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateTypeExtension
 * @package Ekyna\Bundle\CoreBundle\Form\Extension
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class DateTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // http://eonasdan.github.io/bootstrap-datetimepicker/Options/
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

        $view->vars = array_replace($view->vars, [
            'picker_options' => $pickerOptions,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'widget'         => 'single_text',
                'format'         => 'dd/MM/yyyy', // TODO localised configurable format
                'picker_options' => [
                    'widgetPositioning' => ['horizontal' => 'right'],
                    'allowInputToggle'   => true,
                    'showTodayButton'   => true,
                    'showClear'         => true,
                    'showClose'         => true,
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return DateType::class;
    }
}
