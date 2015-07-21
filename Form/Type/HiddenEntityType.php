<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Doctrine\Common\Persistence\ObjectManager;
use Ekyna\Bundle\CoreBundle\Form\DataTransformer\ObjectToIdentifierTransformer;
use Symfony\Bridge\Doctrine\Form\DoctrineOrmTypeGuesser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class HiddenEntityType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class HiddenEntityType extends AbstractType
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var DoctrineOrmTypeGuesser
     */
    private $guesser;

    /**
     * @param ObjectManager $om
     * @param DoctrineOrmTypeGuesser $guesser
     */
    public function __construct(ObjectManager $om, DoctrineOrmTypeGuesser $guesser)
    {
        $this->om = $om;
        $this->guesser = $guesser;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ObjectToIdentifierTransformer();
        $builder->addViewTransformer($transformer);

        if(0 === strlen($options['class'])) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) use ($transformer) {
                $form = $event->getForm();
                $class = $form->getParent()->getConfig()->getDataClass();
                $property = $form->getName();
                if (null === $guessedType = $this->guesser->guessType($class, $property)) {
                    throw new \RuntimeException(sprintf('Unable to guess the type for "%s" property.', $property));
                }
                $typeOptions = $guessedType->getOptions();
                $repository = $this->om->getRepository($typeOptions['class']);
                $transformer->setRepository($repository);
            });
        } else {
            $repository = $this->om->getRepository($options['class']);
            $transformer->setRepository($repository);
        }

        $transformer->setIdentifier($options['identifier']);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'class'    => null,
                'identifier' => 'id',
            ))
            ->setAllowedTypes(array(
                'class'    => array('null', 'string'),
                'identifier' => 'string',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'hidden';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_hidden_entity';
    }
}
