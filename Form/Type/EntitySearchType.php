<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntitySearchType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EntitySearchType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-search'] = $options['search_route'];
        $view->vars['attr']['data-find']   = $options['find_route'];
        $view->vars['attr']['data-clear']  = intval($options['allow_clear']);
        $view->vars['attr']['data-format'] = $options['format_function'];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // TODO Convert options['choice_label'] to javascript options['format_function']...
        // TODO Create a choice_loader (DoctrineType) : only current value and submitted value (for validation)

        $resolver
            ->setDefaults([
                'search_route' => null,
                'find_route'   => null,
                'allow_clear'  => false,
                'format_function' => null,
            ])
            ->setRequired(['search_route', 'find_route'])
            ->setAllowedTypes('search_route', 'string')
            ->setAllowedTypes('find_route', 'string')
            ->setAllowedTypes('allow_clear', 'bool')
            ->setAllowedTypes('format_function', ['null', 'string'])
            /*->setNormalizer('format_function', function(Options $options, $value) {
                if (0 == strlen($value)) {
                    if (0 < strlen($options['choice_label'])) {
                        return 'return data.' . $options['choice_label'] . ';';
                    }

                    throw new InvalidArgumentException("The option 'format_function' must be defined.");
                }

                return $value;
            })*/;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return EntityType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_entity_search';
    }
}
