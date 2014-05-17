<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * UiExtension
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UiExtension extends \Twig_Extension
{
    /**
     * @var \Twig_Template
     */
    private $template;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolver
     */
    private $buttonOptionsResolver;

    public function __construct($template = 'EkynaCoreBundle:Ui:controls.html.twig')
    {
        $this->template = $template;
    }
    
    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        if (! $this->template instanceof \Twig_Template) {
            $this->template = $environment->loadTemplate($this->template);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'ui_form_footer' => new \Twig_Function_Method($this, 'renderFormFooter', array('is_safe' => array('html'))),
            'ui_link'        => new \Twig_Function_Method($this, 'renderLink', array('is_safe' => array('html'))),
            'ui_button'      => new \Twig_Function_Method($this, 'renderButton', array('is_safe' => array('html'))),
        );
    }

    /*public function renderLink($href, $label = '', $theme = 'default', $size = 'sm', $icon = null, $right = false, $faIcon = false, $target = null)
    {
        $rightIcon = $leftIcon = '';
        if(0 < strlen($icon)) {
            $icon = $faIcon ? 'fa fa-'.$icon.'"></span>' : 'glyphicon glyphicon-'.$icon;
            if($right) {
                $rightIcon = sprintf('<span class="%s"></span>', $icon);
            }else{
                $leftIcon = sprintf('<span class="%s"></span>', $icon);
            }
        }

        if(0 < strlen($size)) {
            $class = sprintf('btn btn-%s btn-%s', str_replace('btn-', '', $theme), str_replace('btn-', '', $size));
        }else{
            $class = sprintf('btn btn-%s', str_replace('btn-', '', $theme));
        }

        $target = null !== $target ? sprintf(' target="%s"', $target) : '';

        return trim($this->template->renderBlock('link', array(
            'class' => $class,
            'label' => $label,
            'left_icon' => $leftIcon,
            'right_icon' => $rightIcon,
            'href' => $href,
            'target' => $target
        )));
    }*/

    public function renderFormFooter(FormView $form)
    {
        if(array_key_exists('footer', $form->vars)) {
            return $this->template->renderBlock('form_footer', $form->vars['footer']);
        }
        return '';
    }

    public function renderLink($href, $label = '', array $options = array(), array $attributes = array())
    {
        $options['type'] = 'link';
        $options['path'] = $href;

        return $this->renderButton($label, $options, $attributes);
    }

    public function renderButton($label = '', array $options = array(), array $attributes = array())
    {
        $options = $this->getButtonOptionsResolver()->resolve($options);

        $tag = 'button';
        $classes = array('btn', 'btn-'.$options['theme'], 'btn-'.$options['size']);
        $defaultAttributes = array(
        	'class' => sprintf('btn btn-%s btn-%s', $options['theme'], $options['size']),
        );
        if ($options['type'] == 'link') {
            if (0 == strlen($options['path'])) {
                throw new \InvalidArgumentException('"path" option must be defined for "link" button type.');
            }
            $tag = 'a';
            $defaultAttributes['href'] = $options['path'];
        } else {
            $defaultAttributes['type'] = $options['type'];
        }

        if (array_key_exists('class', $attributes)) {
            $classes = array_merge($classes, explode(' ', $attributes['class']));
            unset($attributes['class']);
        }
        $defaultAttributes['class'] = implode(' ', $classes);
        $attributes = array_merge($defaultAttributes, $attributes);

        $icon = '';
        if(0 < strlen($options['icon'])) {
            $icon = $options['fa_icon'] ? 'fa fa-'.$options['icon'] : 'glyphicon glyphicon-'.$options['icon'];
        }

        return trim($this->template->renderBlock('button', array(
            'tag'   => $tag,
            'attr'  => $attributes,
            'label' => $label,
            'icon'  => $icon,
        )));
    }

    private function getButtonOptionsResolver()
    {
        if (null === $this->buttonOptionsResolver) {
            $this->buttonOptionsResolver = new OptionsResolver();
            $this->buttonOptionsResolver
            ->setDefaults(array(
                'type'    => 'button',
                'theme'   => 'default',
                'size'    => 'sm',
                'icon'    => null,
                'fa_icon' => false,
                'path'    => null,
            ))
            ->setRequired(array('type', 'theme', 'size'))
            ->setAllowedValues(array(
                'type'  => array('link', 'button', 'submit', 'reset'),
                'theme' => array('default', 'primary', 'success', 'warning', 'danger'),
                'size'  => array('xs', 'sm', 'md', 'lg'),
            ))
            ->setAllowedTypes(array(
                'icon' => array('string', 'null'),
                'fa_icon' => 'bool',
                'path' => array('string', 'null'),
            ))
            ;
        }
    
        return $this->buttonOptionsResolver;
    }

    public function getName()
    {
    	return 'ekyna_core_ui';
    }
}