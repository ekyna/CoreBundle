<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ChoiceTypeParentExtension
 * @package Ekyna\Bundle\CoreBundle\Form\Extension
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class ChoiceTypeParentExtension extends AbstractTypeExtension
{
    const BOTH_OR_NONE = 'You may define both "parent_choice_field" and "parent_choice_route", or none of them.';

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'parent_choice_field'     => null,
                'parent_choice_route'     => null,
                'parent_choice_parameter' => 'id',
            ])
            ->setAllowedTypes('parent_choice_field', ['string', 'null'])
            ->setAllowedTypes('parent_choice_route', ['string', 'null'])
            ->setAllowedTypes('parent_choice_parameter', 'string');
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
            $view->vars['attr']['data-parent-choice'] = json_encode([
                'field'     => $view->parent->vars['id'] . '_' . $options['parent_choice_field'],
                'route'     => $options['parent_choice_route'],
                'parameter' => $options['parent_choice_parameter'],
            ]);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return ChoiceType::class;
    }
}
