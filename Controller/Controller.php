<?php

namespace Ekyna\Bundle\CoreBundle\Controller;

use Ekyna\Bundle\CoreBundle\Modal\Modal;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Controller
 * @package Ekyna\Bundle\CoreBundle\Controller
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Controller extends BaseController
{
    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @param mixed $attributes
     * @param mixed $object
     * @return bool
     */
    protected function isGranted($attributes, $object = null)
    {
        return $this->get('security.context')->isGranted($attributes, $object);
    }

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
     * Returns the session.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    protected function getSession()
    {
        return $this->get('session');
    }

    /**
     * Returns the flash bag.
     *
     * @return \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface
     */
    protected function getFlashBag()
    {
        return $this->getSession()->getFlashBag();
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
        $this->getFlashBag()->add($type, $message);
    }

    /**
     * Redirect to the referer.
     *
     * @param string $defaultPath
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function redirectToReferer($defaultPath)
    {
        if (null !== $request = $this->get('request_stack')->getCurrentRequest()) {
            if (0 < strlen($referer = $request->headers->get('referer'))) {
                return $this->redirect($referer);
            }
        }
        return $this->redirect($defaultPath);
    }

    /**
     * Adds http cache tags to the response and sets the shared max age.
     *
     * @todo move into service ekyna_core.http_cache.tag_manager
     * @param Response $response
     * @param array $tags
     * @param int $smaxage
     * @return Response;
     */
    protected function configureSharedCache(Response $response, array $tags = array(), $smaxage = null)
    {
        if (!empty($tags)) {
            $this->get('ekyna_core.cache.tag_manager')->tagResponse($response, $tags);
        }
        $smaxage = intval($smaxage);
        if (0 >= $smaxage) {
            $smaxage = $this->container->getParameter('ekyna_core.cache_config')['default_smaxage'];
        }
        return $response->setSharedMaxAge($smaxage);
    }
}
