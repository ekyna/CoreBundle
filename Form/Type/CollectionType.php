<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Braincrafted\Bundle\BootstrapBundle\Form\Type\BootstrapCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CollectionType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CollectionType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'error_bubbling'        => false,
                'by_reference'          => false,
                'allow_add'             => true,
                'allow_delete'          => true,
                'allow_sort'            => false,
                'add_button_text'       => 'ekyna_core.button.add',
                'delete_button_text'    => 'ekyna_core.button.remove',
                'delete_button_confirm' => 'ekyna_core.message.remove_confirm',
                'sub_widget_col'        => 11,
                'button_col'            => 1,
            ])
            ->setAllowedTypes('allow_sort', 'bool')
            ->setAllowedTypes('delete_button_confirm', ['null', 'string']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['allow_sort'] = $options['allow_sort'];
        $view->vars['delete_button_confirm'] = $options['delete_button_confirm'];

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
        return BootstrapCollectionType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_collection';
    }
}
