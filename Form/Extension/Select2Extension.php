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
            ->setDefault('select2', true)
            ->setAllowedTypes('select2', ['bool', 'array']);
    }

    /**
     * {@inheritDoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (true === $options['expanded']) {
            return;
        }

        if ($select2 = $options['select2']) {
            FormUtil::addClass($view, 'select2');

            if (is_array($select2)) {
                if (!$options['required'] && !isset($select2['allowClear'])) {
                    $select2['allowClear'] = false;
                }

                foreach ($select2 as $key => $value) {
                    $view->vars['attr']['data-' . $this->dasherize($key)] = (string)$value;
                }
            } elseif (!$options['required']) {
                $view->vars['attr']['data-allow-clear'] = 1;
            }
        }
    }

    /**
     * Dasherizes the given text.
     *
     * @param string $text
     *
     * @return string
     */
    public function dasherize($text)
    {
        return trim(strtolower(preg_replace(['/([A-Z])/', '/[-_\s]+/'], ['-$1', '-'], $text)), ' -');
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
        return ChoiceType::class;
    }
}
