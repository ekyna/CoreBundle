<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * FormTypeExtension.
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FormTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'sizing' => null,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['sizing'] = in_array($options['sizing'], array('sm', 'lg')) ? $options['sizing'] : false;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
