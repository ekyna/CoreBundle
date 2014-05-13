<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * FormTypeRedirectExtension.
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FormTypeRedirectExtension extends AbstractTypeExtension
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * Constructor.
     * 
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
        // Abort if not enabled
        if (! (array_key_exists('_redirect_enabled', $options) && $options['_redirect_enabled'] === true)) {
            return;
        }

        // Retrieve the _redirect path from request (GET)
        $redirectPath = null;
        if (null !== $request = $this->requestStack->getCurrentRequest()) {
            $redirectPath = $request->query->get('_redirect', null);
        }

        // Add the hidden field
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $form->add('_redirect', 'hidden', array('mapped' => false));
            }
        );

        // Sets the data
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($redirectPath, $request) {
                $form = $event->getForm();
                // If form has been posted => retrieve _redirect path from request (POST)
                if(null === $redirectPath && null !== $request) {
                    $redirectPath = $request->request->get($form->getName(), array('_redirect' => null))['_redirect'];
                }
                $form->get('_redirect')->setData($redirectPath);
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setOptional(array('_redirect_enabled'))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedType()
    {
    	return 'form';
    }
}
