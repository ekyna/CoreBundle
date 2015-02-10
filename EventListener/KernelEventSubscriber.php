<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Exception\RedirectException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Class KernelEventSubscriber
 * @package Ekyna\Bundle\CoreBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class KernelEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var HttpUtils
     */
    private $httpUtils;

    /**
     * Constructor.
     * 
     * @param Session $session
     * @param HttpUtils $httpUtils
     */
    public function __construct(Session $session, HttpUtils $httpUtils)
    {
        $this->session = $session;
        $this->httpUtils = $httpUtils;
    }

    /**
     * Kernel exception event handler.
     * 
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if ($exception instanceof RedirectException) {
            // Check path
            $path = $exception->getPath();
            if (0 === strlen($path)) {
                return;
            }

            // Build the response
            $request = $event->getRequest();
            $response = $this->httpUtils->createRedirectResponse($request, $path);
            $event->setResponse($response);

            // Add flash
            if (0 < strlen($message = $exception->getMessage())) {
                $this->session->getFlashBag()->add($exception->getMessageType(), $message);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
    	return array(
            KernelEvents::EXCEPTION => array('onKernelException', 0),
    	);
    }
}
