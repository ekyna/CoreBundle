<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ChoiceTypeParentExtension
 * @package Ekyna\Bundle\CoreBundle\Form\Extension
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ChoiceTypeParentExtension extends AbstractTypeExtension
{
    const BOTH_OR_NONE = 'You may define both "parent_choice_field" and "parent_choice_route", or none of them.';

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'parent_choice_field' => null,
                'parent_choice_route' => null,
            ))
            ->setOptional(array('parent_choice_field', 'parent_choice_route'))
            ->setAllowedTypes(array(
                'parent_choice_field' => array('string', 'null'),
                'parent_choice_route' => array('string', 'null'),
            ))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $fieldDefined = 0 < strlen($options['parent_choice_field']);
        $routeDefined = 0 < strlen($options['parent_choice_route']);

        if (($fieldDefined && !$routeDefined) || (!$fieldDefined && $routeDefined)) {
            throw new MissingOptionsException(self::BOTH_OR_NONE);
        }

        if ($fieldDefined && $routeDefined) {
            $view->vars['attr']['data-parent-choice'] = json_encode(array(
                'field' => $view->parent->vars['id'].'_'.$options['parent_choice_field'],
                'route' => $options['parent_choice_route'],
            ));
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return 'choice';
    }
}
