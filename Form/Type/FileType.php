<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Class FileType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FileType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('key', 'hidden');

        if ($options['rename_field']) {
            $builder->add('rename', 'text', array(
                'label' => 'ekyna_core.field.rename',
                'required' => $options['required'],
                'sizing' => 'sm',
                'admin_helper' => 'FILE_RENAME',
                'attr' => array(
                    'class' => 'file-rename',
                    'label_col' => 2,
                    'widget_col' => 10
                ),
            ));
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($options) {
                $form = $event->getForm();
                /** @var \Ekyna\Bundle\CoreBundle\Model\FileInterface $file */
                $file = $event->getData();

                if (null !== $file && null !== $file->getPath()) {
                    $form->add('file', 'file', array(
                        'label' => 'ekyna_core.field.file',
                        'required' => false,
                        'sizing' => 'sm',
                        'admin_helper' => 'FILE_UPLOAD',
                        'attr' => array(
                            'label_col' => 2,
                            'widget_col' => 10
                        )
                    ));
                } else {
                    $form->add('file', 'file', array(
                        'label' => 'ekyna_core.field.file',
                        'required' => false,
                        'sizing' => 'sm',
                        'admin_helper' => 'FILE_UPLOAD',
                        'attr' => array(
                            'label_col' => 2,
                            'widget_col' => 10
                        )
                    ));
                }
            }
        );
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
        $view->children['key']->vars['attr']['data-target'] = $view->children['file']->vars['id'];
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'label'        => 'ekyna_core.field.file',
                'data_class'   => null,
                'file_path'    => 'path',
                'rename_field' => true,
            ))
            ->setRequired(array('data_class'))
            ->setAllowedTypes(array(
                'data_class'   => 'string',
                'file_path'    => array('null', 'string'),
                'rename_field' => 'bool',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_core_file';
    }
}
