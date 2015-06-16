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
                'error_bubbling'     => false,
                'by_reference'       => false,
                'add_button_text'    => 'ekyna_core.button.add',
                'delete_button_text' => 'ekyna_core.button.remove',
                'allow_add'          => true,
                'allow_delete'       => true,
                'allow_sort'         => false,
                'remove_confirm'     => 'ekyna_core.message.remove_confirm',
            ))
            ->setAllowedTypes(array(
                'allow_sort' => 'bool',
                'remove_confirm' => array('null', 'string'),
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_sort'] = $options['allow_sort'];
        $view->vars['remove_confirm'] = $options['remove_confirm'];

        if (false === $view->vars['allow_delete'] && false === $view->vars['allow_sort']) {
            $view->vars['sub_widget_col'] += $view->vars['button_col'];
            $view->vars['button_col'] = 0;
            if ($view->vars['sub_widget_col'] > 12) {
                $view->vars['sub_widget_col'] = 12;
            }
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
        return 'ekyna_collection';
    }
}
