<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Ekyna\Bundle\CoreBundle\Form\DataTransformer\UploadableToNullTransformer;
use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class UploadType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class UploadType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', Type\FileType::class, [
            'label'        => 'ekyna_core.field.file',
            'required'     => false,
            'admin_helper' => 'FILE_UPLOAD',
            'attr'         => [
                'label_col'  => 2,
                'widget_col' => 10,
            ],
        ]);

        if ($options['js_upload']) {
            $builder->add('key', Type\HiddenType::class);
        }

        if ($options['rename_field']) {
            $builder->add('rename', Type\TextType::class, [
                'label'        => 'ekyna_core.field.rename',
                'required'     => $options['required'],
                'admin_helper' => 'FILE_RENAME',
                'attr'         => [
                    'class'      => 'file-rename',
                    'label_col'  => 2,
                    'widget_col' => 10,
                ],
            ]);
        }

        if ($options['unlink_field']) {
            $builder->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($options) {
                    $form = $event->getForm();
                    /** @var \Ekyna\Bundle\CoreBundle\Model\UploadableInterface $uploadable */
                    $uploadable = $event->getData();

                    if (null !== $uploadable && null !== $uploadable->getPath()) {
                        $form->add('unlink', Type\CheckboxType::class, [
                            'label'        => 'ekyna_core.field.unlink',
                            'required'     => false,
                            'admin_helper' => 'FILE_UNLINK',
                            'attr'         => [
                                'label_col'         => 2,
                                'widget_col'        => 10,
                                'align_with_widget' => true,
                            ],
                        ]);
                    }
                }
            );
        }

        $builder->addModelTransformer(new UploadableToNullTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('file_path', $options) && 0 < strlen($filePath = $options['file_path'])) {
            $data = $form->getData();
            $currentPath = null;
            $currentName = null;
            if (null !== $data) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $currentPath = $accessor->getValue($data, $filePath);
                $currentName = pathinfo($currentPath, PATHINFO_BASENAME);
            }
            $view->vars['current_file_path'] = $currentPath;
            $view->vars['current_file_name'] = $currentName;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['js_upload']) {
            $view->children['key']->vars['attr']['data-target'] = $view->children['file']->vars['id'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'label'          => 'ekyna_core.field.file',
                'data_class'     => UploadableInterface::class,
                'file_path'      => 'path',
                'rename_field'   => true,
                'unlink_field'   => false,
                'js_upload'      => true,
                'error_bubbling' => false,
            ])
            ->setAllowedTypes('file_path', ['null', 'string'])
            ->setAllowedTypes('rename_field', 'bool')
            ->setAllowedTypes('unlink_field', 'bool')
            ->setAllowedTypes('js_upload', 'bool');
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_upload';
    }
}
