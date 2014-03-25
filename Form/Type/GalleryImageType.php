<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Ekyna\Bundle\CoreBundle\Form\Type\ImageType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Gallery Image Form Type
 */
class GalleryImageType extends ImageType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('position', 'hidden', array('attr' => array('data-role' => 'position')))
        ;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ekyna_gallery_image';
    }
}
