<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Ekyna\Bundle\CoreBundle\Form\DataTransformer\ObjectToIdentifierTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntitySearchType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EntitySearchType extends AbstractType
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    protected $om;

    /**
     * Constructor.
     * 
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $repository = $this->om->getRepository($options['entity']);
        $builder
            ->addModelTransformer(
                new ObjectToIdentifierTransformer($repository)
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr']['data-search'] = $options['search_route'];
        $view->vars['attr']['data-find']   = $options['find_route'];
        $view->vars['attr']['data-clear']  = intval($options['allow_clear']);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'entity'       => null,
                'search_route' => null,
                'find_route'   => null,
                'allow_clear'  => false,
            ])
            ->setRequired(['entity', 'search_route', 'find_route'])
            ->setAllowedTypes('entity',        'string')
            ->setAllowedTypes('search_route',  'string')
            ->setAllowedTypes('find_route',    'string')
            ->setAllowedTypes('allow_clear',   'bool')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'ekyna_entity_search';
    }
}
