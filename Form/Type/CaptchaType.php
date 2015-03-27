<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class CaptchaType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class CaptchaType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'label' => false,
                'height' => 34,
                'attr' => array(
                    'help_text' => 'ekyna_core.message.captcha',
                )
            ))
        ;
    }

    public function getParent()
    {
        return 'captcha';
    }

    public function getName()
    {
        return 'ekyna_captcha';
    }
}