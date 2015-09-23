<?php

namespace Ekyna\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Locale\Locale;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddressType
 * @package Ekyna\Bundle\CoreBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AddressType extends AbstractType
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('street', 'text', [
                'label' => 'ekyna_core.field.street',
                'attr' => ['data-role' => 'street'],
            ])
            ->add('supplement', 'text', [
                'label' => 'ekyna_core.field.supplement',
                'required' => false,
            ])
            ->add('postalCode', 'text', [
                'label' => 'ekyna_core.field.postal_code',
                'attr' => ['data-role' => 'postal-code'],
            ])
            ->add('city', 'text', [
                'label' => 'ekyna_core.field.city',
                'attr' => ['data-role' => 'city'],
            ])
        ;
        if ($options['country']) {
            $countryOptions = [
                'label' => 'ekyna_core.field.country',
                'attr'  => ['data-role' => 'country'],
                'empty_data' => null,
                'empty_value' => 'ekyna_core.value.choose',
            ];
            if ($options['required']) {
                if (null !== $request = $this->requestStack->getMasterRequest()) {
                    $countryOptions['data'] = strtoupper($request->getLocale());
                } else {
                    $countryOptions['data'] = strtoupper(Locale::getDefault());
                }
            }
            $builder
                ->add('country', 'country', $countryOptions)
                ->add('state', 'text', [
                    'label'    => 'ekyna_core.field.state',
                    'attr'     => ['data-role' => 'state'],
                    'required' => false,
                ])
            ;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'country' => true,
            ])
            ->addAllowedTypes([
                'country' => 'bool',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_address';
    }
}
