<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Ekyna\Bundle\CoreBundle\Form\DataTransformer\ObjectToIdentifierTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class EntitySearchType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class EntitySearchType extends AbstractType
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var SerializerInterface
     */
    private $serializer;


    /**
     * Constructor.
     *
     * @param Registry            $registry
     * @param SerializerInterface $serializer
     */
    public function __construct(Registry $registry, SerializerInterface $serializer)
    {
        $this->registry = $registry;
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->registry->getRepository($options['class']);

        $builder->addViewTransformer(new ObjectToIdentifierTransformer($repository));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $value = null;
        $choices = [];
        if (null !== $entity = $form->getData()) {
            $choiceLabel = $options['choice_label'];
            if (is_callable($choiceLabel)) {
                $label = $choiceLabel($entity);
            } elseif (is_string($choiceLabel) && 0 < strlen($choiceLabel)) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $label = (string)$accessor->getValue($entity, $choiceLabel);
            } else {
                $label = (string)$entity;
            }

            $repository = $this->registry->getRepository($options['class']);
            $transformer = new ObjectToIdentifierTransformer($repository);
            $value = $transformer->transform($entity);

            $choices[] = new ChoiceView($entity, $value, $label, [
                'data-serialized' => $this->serializer->serialize($entity, 'json', ['groups' => ['Search']]),
            ]);
        }

        $view->vars['value'] = $value;
        $view->vars['choices'] = $choices;
        $view->vars['preferred_choices'] = [];
        $view->vars['placeholder'] = 'ekyna_core.field.search';
        $view->vars['multiple'] = false;
        $view->vars['expanded'] = false;

        $view->vars['attr']['data-config'] = json_encode(array_intersect_key($options, array_flip([
            'route', 'route_params', 'allow_clear', 'format',
        ])));

        if (0 < strlen($options['add_route'])) {
            $view->vars['add_route'] = $options['add_route'];
            $view->vars['add_route_params'] = $options['add_route_params'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'compound'         => false,
                'route'            => null,
                'route_params'     => [],
                'add_route'        => false,
                'add_route_params' => [],
                'choice_label'     => null,
                'format'           => null,
                'allow_clear'      => function (Options $options, $value) {
                    if (!$value) {
                        return !$options['required'];
                    }

                    return $value;
                },
            ])
            ->setRequired(['class', 'route'])
            ->setAllowedTypes('class', 'string')
            ->setAllowedTypes('route', 'string')
            ->setAllowedTypes('route_params', 'array')
            ->setAllowedTypes('allow_clear', 'bool')
            ->setAllowedTypes('choice_label', ['null', 'string', 'callable'])
            ->setAllowedTypes('format', ['null', 'string'])
            ->setNormalizer('format', function (Options $options, $value) {
                if (!empty($value)) {
                    return $value;
                }

                $field = 'choice_label';
                if (is_string($options['choice_label']) && 0 < strlen($options['choice_label'])) {
                    $field = $options['choice_label'];
                }

                return "if(!data.id)return 'Search'; return data.$field;"; // TODO Camelcase to underscore ?
            });
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'ekyna_entity_search';
    }
}
