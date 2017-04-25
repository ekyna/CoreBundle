<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Ekyna\Bundle\CoreBundle\Model\FAIcons;
use Ekyna\Bundle\ResourceBundle\Form\Type\ConstantChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FAIconChoiceType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FAIconChoiceType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'label'        => 'ekyna_core.field.icon',
                'class'        => FAIcons::class,
                'admin_helper' => 'CMS_TAG_ICON',
                'placeholder'  => 'ekyna_core.value.choose',
                'select2'      => false,
                'attr'         => [
                    'class' => 'fa-icon-choice',
                    'help_text' => 'ekyna_core.message.fa_icon_cheatsheet',
                ],
            ]);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return ConstantChoiceType::class;
    }
}
