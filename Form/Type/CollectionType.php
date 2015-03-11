<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CollectionType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CollectionType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'allow_sort' => false,
            ))
            ->setAllowedTypes(array(
                'allow_sort' => 'bool',
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_sort'] = $options['allow_sort'];

        if (false === $view->vars['allow_delete'] && false === $view->vars['allow_sort']) {
            $view->vars['sub_widget_col'] += $view->vars['button_col'];
            $view->vars['button_col'] = 0;
        } else {
            $view->vars['sub_widget_col'] = $options['sub_widget_col'];
            $view->vars['button_col'] = $options['button_col'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'bootstrap_collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_core_collection';
    }
}
