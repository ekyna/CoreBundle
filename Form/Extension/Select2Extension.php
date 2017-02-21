<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Ekyna\Bundle\CoreBundle\Form\Util\FormUtil;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Select2Extension
 * @package Ekyna\Bundle\CoreBundle\Form\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Select2Extension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'select2' => true,
            ])
            ->setAllowedTypes('select2', ['bool', 'array']);
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if ($select2 = $options['select2']) {
            FormUtil::addClass($view, 'select2');

            $allowClear = !$options['required'];

            if (is_array($select2)) {
                if (isset($select2['allow-clear'])) {
                    $allowClear = $select2['allow-clear'];
                }
            }

            $view->vars['attr']['data-allow-clear'] = $allowClear ? 1 : 0;
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
