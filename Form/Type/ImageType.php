<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Image Form Type
 */
class ImageType extends AbstractType
{
    /**
     * The image class
     * 
     * @var string
     */
    protected $dataClass;

    /**
     * Constructor
     * 
     * @param string $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', array(
                'label' => 'ekyna.core.field.file.label',
                'required' => false,
                'sizing' => 'sm'
            ))
            ->add('name', 'text', array(
                'label' => 'ekyna.core.field.name.label',
                'sizing' => 'sm',
                'attr' => array(
                    'class' => 'rename-widget'
                )
            ))
            ->add('alt', 'text', array(
                'label' => 'ekyna.core.field.alt.label',
                'sizing' => 'sm'
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => $this->dataClass,
                'image_path' => 'path',
            ))
            ->setOptional(array('image_path'));
        ;
    }

    /**
     * Passe l'url de l'image à la vue
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
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
     * @return string
     */
    public function getName()
    {
        return 'ekyna_image';
    }
}
