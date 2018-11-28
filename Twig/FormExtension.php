<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

/**
 * Class FormExtension
 * @package Ekyna\Bundle\CoreBundle\Twig
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FormExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('form_help', null, array('node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html'))),
        );
    }
}
