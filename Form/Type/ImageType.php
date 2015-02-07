<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class ImageType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['alt_field']) {
            $builder->add('alt', 'text', array(
                'label' => 'ekyna_core.field.alt',
                'required' => false,
                'sizing' => 'sm',
                'admin_helper' => 'IMAGE_ALT',
                'attr' => array(
                    'label_col' => 2,
                    'widget_col' => 10
                ),
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'label' => 'ekyna_core.field.image',
                'display_thumb' => true,
                'image_path' => 'path',
                'alt_field'  => true,
            ))
            ->setRequired(array('data_class'))
            ->setOptional(array('image_path'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('image_path', $options)) {
            $data = $form->getData();
            if (null !== $data) {
               $accessor = PropertyAccess::createPropertyAccessor();
               $imageUrl = $accessor->getValue($data, $options['image_path']);
            } else {
                $imageUrl = null;
            }
            $view->vars['image_path'] = $imageUrl;
        }
        $view->vars['display_thumb'] = $options['display_thumb'];
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'ekyna_core_file';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_core_image';
    }
}
