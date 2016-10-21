<?php

namespace Ekyna\Bundle\CoreBundle\Form\Util;

use Symfony\Component\Form\FormView;

/**
 * Class FormAttributes
 * @package Ekyna\Bundle\CoreBundle\Form\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FormAttributes
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
}
