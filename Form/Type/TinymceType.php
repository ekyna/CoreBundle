<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TinymceType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class TinymceType extends AbstractType
{
    /**
     * @var array
     */
    private $themes;

    /**
     * Constructor.
     *
     * @param array $themes
     */
    public function __construct(array $themes)
    {
        $this->themes = $themes;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $builder->addModelTransformer(new CallbackTransformer(
            function ($html) { // transform
                return $html;
            },
            function ($html) { // reverse transform
                $html = preg_replace('~<p[^>]*>[&nbsp;|\s]*</p>~', '', $html);
                $html = preg_replace('~[\r\n]+~', '', $html);

                if (0 === strlen($html)) {
                    return null;
                }

                return $html;
            }
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $attrNormalizer = function (Options $options, $value) {
            $theme = isset($options['theme'])
            && is_string($options['theme'])
            && in_array($options['theme'], $this->themes)
                ? $options['theme'] : 'simple';

            if (array_key_exists('class', $value) && 0 < strlen($value['class'])) {
                $value['class'] .= ' tinymce';
            } else {
                $value['class'] = 'tinymce';
            }

            $value['data-theme'] = $theme;

            return $value;
        };

        $resolver
            ->setDefaults([
                'theme' => 'simple',
            ])
            ->setAllowedTypes('theme', 'string')
            ->setNormalizer('attr', $attrNormalizer);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextareaType::class;
    }
}
