<?php

namespace Ekyna\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

/**
 * Class Controller
 * @package Ekyna\Bundle\CoreBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Controller extends BaseController
{
    /**
     * Returns the router.
     *
     * @return \Symfony\Component\Routing\RouterInterface;
     */
    protected function getRouter()
    {
        return $this->get('router');
    }

    /**
     * Returns the event dispatcher.
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getDispatcher()
    {
        return $this->get('event_dispatcher');
    }

    /**
     * Returns the validator.
     *
     * @return \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected function getValidator()
    {
        return $this->get('validator');
    }

    /**
     * Returns the translator.
     *
     * @return \Symfony\Component\Translation\TranslatorInterface
     */
    protected function getTranslator()
    {
        return $this->get('translator');
    }

    /**
     * Adds a flash message.
     *
     * @param string $message
     * @param string $type (info|success|warning|danger)
     * @throws \InvalidArgumentException
     */
    protected function addFlash($message, $type = 'info')
    {
        if (!in_array($type, array('info', 'success', 'warning', 'danger'))) {
            throw new \InvalidArgumentException(sprintf('Invalid flash type "%s".', $type));
        }
        $this->get('session')->getFlashBag()->add($type, $message);
    }
}
