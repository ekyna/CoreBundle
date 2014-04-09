<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

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
     * @var string
     */
    private $prefix;

    public function __construct($template = 'EkynaCoreBundle:Ui:controls.html.twig', $prefix = 'ui')
    {
        $this->template = $template;
        $this->prefix = $prefix;
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
            $this->prefix.'_link'  => new \Twig_Function_Method($this, 'renderLink', array('is_safe' => array('html'))),
            $this->prefix.'_button'  => new \Twig_Function_Method($this, 'renderButton', array('is_safe' => array('html'))),
            $this->prefix.'_form_footer'  => new \Twig_Function_Method($this, 'renderFormFooter', array('is_safe' => array('html'))),
        );
    }

    public function renderLink($href, $label = '', $theme = 'default', $size = 'sm', $icon = null, $right = false, $faIcon = false)
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

        return trim($this->template->renderBlock('link', array(
            'class' => $class,
            'label' => $label,
            'left_icon' => $leftIcon,
            'right_icon' => $rightIcon,
            'href' => $href,
        )));
    }

    public function renderButton($label = '', $theme = 'default', $size = 'sm', $type = 'button', $id = null, $icon = null, $right = false, $faIcon = false)
    {
        $rightIcon = $leftIcon = '';
        if(0 < strlen($icon)) {
            $icon = $faIcon ? 'fa fa-'.$icon : 'glyphicon glyphicon-'.$icon;
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

        return trim($this->template->renderBlock('button', array(
            'class' => $class,
            'id' => $id,
            'label' => $label,
            'left_icon' => $leftIcon,
            'right_icon' => $rightIcon,
            'type' => $type,
        )));
    }

    public function renderFormFooter($path = null, $label = 'Enregistrer', $theme = 'primary', $icon = 'ok', $faIcon = false, $offset = 2)
    {
        if(0 < strlen($icon)) {
            $icon = $faIcon ? 'fa fa-'.$icon : 'glyphicon glyphicon-'.$icon;
            $icon = sprintf('<span class="%s"></span>', $icon);
        }
        return $this->template->renderBlock('form_footer', array(
            'path' => $path,
            'label' => $label,
            'theme' => $theme,
            'icon' => $icon,
            'offset' => $offset,
        ));
    }

    public function getName()
    {
    	return 'ekyna_core_ui';
    }
}