<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class EntityTypeExtension
 * @package Ekyna\Bundle\CoreBundle\Form\Extension
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EntityTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'add_route' => false,
                'add_route_params' => array(),
            ))
            ->setOptional(array('add_route', 'add_route_params'))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (0 < strlen($options['add_route'])) {
            $view->vars['add_route'] = $options['add_route'];
            $view->vars['add_route_params'] = $options['add_route_params'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'entity';
    }
}
