<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * ImageType
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'ekyna.core.field.file.label',
                'required' => false,
                'sizing' => 'sm',
                'attr' => array(
                    'widget_col' => 2,
                    'widget_col' => 10
                )
            ))
            ->add('name', 'text', array(
                'label' => 'ekyna.core.field.name.label',
                'sizing' => 'sm',
                'attr' => array(
                    'class' => 'rename-widget',
                    'widget_col' => 2,
                    'widget_col' => 10
                )
            ))
            ->add('alt', 'text', array(
                'label' => 'ekyna.core.field.alt.label',
                'sizing' => 'sm',
                'attr' => array(
                    'widget_col' => 2,
                    'widget_col' => 10
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => null,
                'image_path' => 'path',
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
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_core_image';
    }
}
