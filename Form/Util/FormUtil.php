<?php

namespace Ekyna\Bundle\CoreBundle\Form\Util;

use Symfony\Component\Form\FormConfigBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormView;

/**
 * Class FormAttributes
 * @package Ekyna\Bundle\CoreBundle\Form\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FormUtil
{
    /**
     * ADds the class the form view's attributes.
     *
     * @param FormView $view
     * @param string   $class
     */
    static public function addClass(FormView $view, $class)
    {
        $attributes = $view->vars['attr'];

        $classes = isset($attributes['class']) ? $attributes['class'] : '';
        if (false === strpos($classes, $class)) {
            $classes = trim($classes . ' ' . $class);
        }
        $attributes['class'] = $classes;

        $view->vars['attr'] = $attributes;
    }

    /**
     * Binds the form events to children form fields.
     *
     * Child form with 'inherit_data' = true do not receive form events.
     * @see https://github.com/symfony/symfony/issues/8834#issuecomment-55785696
     *
     * @param FormConfigBuilderInterface $builder  The parent form builder.
     * @param array                      $events   The names of the events to bind ([$name => $priority]).
     * @param array                      $children The names of the children fields.
     */
    static function bindFormEventsToChildren(FormConfigBuilderInterface $builder, $events, array $children)
    {
        if (!is_array($events)) {
            $events = [$events];
        }

        if (empty($events) || empty($children)) {
            return;
        }

        foreach ($events as $name => $priority) {
            if (is_int($name) && is_scalar($priority)) {
                $name = $priority;
                $priority = 0;
            }

            $builder->addEventListener($name, function (FormEvent $event) use ($name, $children) {
                $form = $event->getForm();
                foreach ($children as $field) {
                    $child = $form->get($field);
                    $child->getConfig()->getEventDispatcher()->dispatch(
                        $name,
                        new FormEvent($child, $event->getData())
                    );
                }
            }, $priority);
        }
    }
}
