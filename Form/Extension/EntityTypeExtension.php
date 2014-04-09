<?php

namespace Ekyna\Bundle\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * EntityTypeExtension
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EntityTypeExtension extends AbstractTypeExtension
{
    /**
     * Ajoute l'option allow_add
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(array(
            'add_route'
        ));
        $resolver->setDefaults(array(
            'add_route' => false
        ));
    }

    /**
     * Ajoute les variables à la vue
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['add_route'] = $options['add_route'];
    }

    /**
     * @return string Le nom du type qui est étendu
     */
    public function getExtendedType()
    {
        return 'entity';
    }
}
