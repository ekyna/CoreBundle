<?php

namespace Ekyna\Bundle\CoreBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class FormExtension
 * @package Ekyna\Bundle\CoreBundle\Twig
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FormExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('form_help', null, [
                'node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode',
                'is_safe' => ['html']
            ]),
        ];
    }
}
