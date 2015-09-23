<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FormTypeSizingExtension
 * @package Ekyna\Bundle\CoreBundle\Form\Extension
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FormTypeSizingExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'sizing' => null,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['sizing'] = in_array($options['sizing'], ['xs', 'sm', 'md', 'lg']) ? $options['sizing'] : false;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'form';
    }
}
