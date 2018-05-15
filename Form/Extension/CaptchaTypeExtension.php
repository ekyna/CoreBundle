<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Gregwar\CaptchaBundle\Type\CaptchaType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CaptchaTypeExtension
 * @package Ekyna\Bundle\CoreBundle\Form\Extension
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class CaptchaTypeExtension extends AbstractTypeExtension
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'height'           => 44,
            'width'            => 140,
            'background_color' => [255, 255, 255],
            'as_url'           => true,
            'reload'           => true,
            'attr'             => [
                'help_text' => 'ekyna_core.message.captcha',
            ],
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getExtendedType()
    {
        return CaptchaType::class;
    }
}