<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;
use Ekyna\Bundle\CoreBundle\Form\Util\Datetime;

/**
 * DatetimeTypeExtension.
 *
 * @author Stephane Collot
 * @see https://github.com/stephanecollot/DatetimepickerBundle/blob/master/Form/Type/DatetimeType.php
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class DatetimeTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $pickerOptions = $options['pickerOptions'];

        // Set automatically the language
        if (!isset($options['pickerOptions']['language'])) {
            $pickerOptions['language'] = \Locale::getDefault();
        }
        if ($pickerOptions['language'] == 'en') {
            unset($pickerOptions['language']);
        }

        // Set the defaut format of malot.fr/bootstrap-datetimepicker
        if (!isset($options['pickerOptions']['format'])) {
            $pickerOptions['format'] = 'dd/mm/yyyy hh:ii';
        }

        $view->vars = array_replace($view->vars, array(
            'pickerOptions' => $pickerOptions,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'read_only' => true,
            'format' => function (Options $options, $value) {
                if (isset($options['pickerOptions']['format'])) {
                    return Datetime::convertMalotToIntlFormater( $options['pickerOptions']['format'] );
                } else {
                    return Datetime::convertMalotToIntlFormater( 'dd/mm/yyyy hh:ii' );
                }
            },
            'pickerOptions' => array(
            	'pickerPosition' => 'bottom-left',
                'autoclose' => true,
            ),
        ));
    }

    /**
     * @return string Le nom du type qui est étendu
     */
    public function getExtendedType()
    {
        return 'datetime';
    }
}
