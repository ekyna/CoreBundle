<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\OptionsResolver\Options;

/**
 * FormTypeFooterExtension.
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FormTypeFooterExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // Only "root" form with _footer option
        if(!(null === $form->getParent() && array_key_exists('_footer', $options))) {
            return;
        }

        $footerOptions = $this->resolveFooterOptions($options['_footer'], $form);

        $view->vars['footer'] = $footerOptions;
    }

    private function resolveFooterOptions(array $options, FormInterface $form)
    {
        $buttonResolver = new OptionsResolver();
        $buttonResolver
            ->setDefaults(array(
                'type'  => 'submit',
                'theme' => 'primary',
                'icon'  => 'ok',
                'path'  => '',
            ))
            ->setRequired(array('name', 'type', 'label', 'theme', 'icon'))
            ->setOptional(array('path'))
            ->setAllowedTypes(array(
                'name'  => 'string',
                'type'  => 'string',
                'label' => 'string',
                'theme' => 'string',
                'icon'  => 'string',
                'path'  => 'string',
            ))
            ->setAllowedValues(array(
                'type'  => array('submit', 'link'),
                'theme' => array('primary', 'default', 'success', 'warning', 'danger'),
            ))
            ->setNormalizers(array(
            	'path' => function(Options $options, $value) {
            	    if ('link' === $options['type'] && 0 === strlen($value)) {
            	        throw new InvalidOptionsException('"path" option is mandatory for "link" type buttons.');
            	    }
            	    return $value;
            	}
            ))
        ;

        // Submit default button
        $defaultButtons = array(
            'submit' => array(
    	        'type'  => 'submit',
    	        'label' => 'ekyna_core.button.save',
    	        'theme' => 'primary',
    	        'icon'  => 'ok',
    	    )
        );

        // Cancel default button
        $cancelPath = null;
        // Look for _redirect
        if ($form->has('_redirect')) {
            if (0 < strlen($redirect = $form->get('_redirect')->getData())) {
                $cancelPath = $redirect;
            }
        }
        // Cancel path from options
        if (null === $cancelPath && array_key_exists('cancel_path', $options)) {
            $cancelPath = $options['cancel_path'];
        }

        // if cancel path is defined create default button
        if (null !== $cancelPath) {
            $defaultButtons['cancel'] = array(
    	        'type'  => 'link',
    	        'label' => 'ekyna_core.button.cancel',
    	        'theme' => 'default',
    	        'icon'  => 'ok',
                'path' => $cancelPath,
    	    );
        }

        // Merge buttons options
        if (array_key_exists('buttons', $options)) {
            foreach($defaultButtons as $name => $button) {
                if (array_key_exists($name, $options['buttons'])) {
                    $defaultButtons[$name] = array_merge($button, $options['buttons'][$name]);
                }
            }
        }
        $options['buttons'] = $defaultButtons;

        $resolver = new OptionsResolver();
        $resolver
            ->setDefaults(array(
                'offset'      => 2,
            	'buttons'     => $defaultButtons,
            ))
            ->setOptional(array('cancel_path'))
            ->setRequired(array('buttons'))
            ->setAllowedTypes(array(
                'offset'      => 'int',
                'cancel_path' => 'string',
            	'buttons'     => 'array',
            ))
            ->setNormalizers(array(
            	'buttons' => function(Options $options, $buttons) use ($buttonResolver) {
            	    $buttonsOption = array();
            	    foreach($buttons as $name => $button) {
            	        $button['name'] = $name;
            	        $buttonsOption[] = $buttonResolver->resolve($button);
            	    }
            	    return $buttonsOption;
            	}
            ))
        ;

        return $resolver->resolve($options);
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setOptional(array('_footer'))
            ->setAllowedTypes(array('_footer' => 'array'))
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
