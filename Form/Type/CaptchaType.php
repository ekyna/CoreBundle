<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Gregwar\CaptchaBundle\Type\CaptchaType as BaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CaptchaType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class CaptchaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'label'  => false,
                'height' => 34,
                'attr'   => [
                    'help_text' => 'ekyna_core.message.captcha',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return BaseType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'captcha';
    }
}
