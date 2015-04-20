<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Exception\RedirectException;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelEventSubscriber
 * @package Ekyna\Bundle\CoreBundle\EventListener
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class KernelEventSubscriber implements EventSubscriberInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Kernel exception event handler.
     * 
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof NotFoundHttpException) {

            $request = $event->getRequest();
            $registry = $this->container->get('ekyna_core.redirection.provider_registry');
            foreach ($registry->getProviders() as $provider) {
                if ($provider->supports($request) && false !== $response = $provider->redirect($request)) {
                    if ($response instanceof RedirectResponse) {
                        $event->setResponse($response);
                    } elseif (is_string($response) && 0 < strlen($response)) {
                        $response = $this->container
                            ->get('security.http_utils')
                            ->createRedirectResponse($request, $response, 301)
                        ;
                        $event->setResponse($response);
                    }
                    return;
                }
            }

        } elseif ($exception instanceof RedirectException) {

            // Check path
            $path = $exception->getPath();
            if (0 === strlen($path)) {
                return;
            }

            // Build the response
            $request = $event->getRequest();
            $response = $this->container
                ->get('security.http_utils')
                ->createRedirectResponse($request, $path)
            ;
            $event->setResponse($response);

            // Add flash
            if (0 < strlen($message = $exception->getMessage())) {
                $this->container
                    ->get('session')
                    ->getFlashBag()
                    ->add($exception->getMessageType(), $message)
                ;
            }

        } elseif ($exception instanceof HttpException) {

            // Don't send log about http exceptions.
            return;

        } elseif(!$this->container->getParameter('kernel.debug')) {

            $template = new TemplateReference('EkynaCoreBundle', 'Exception', 'exception', 'html', 'twig');
            $code = $exception->getCode();
            $email = $this->container->getParameter('error_report_mail');
            $request = $this->container->get('request_stack')->getMasterRequest();

            $content = $this->container->get('twig')->render(
                (string) $template,
                array(
                    'status_code' => $code,
                    'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                    'exception' => FlattenException::create($exception),
                    'request' => $request,
                    'logger' => null,
                    'currentContent' => null,
                )
            );

            $report = \Swift_Message::newInstance('Error report', $content, 'text/html');
            $report->setFrom($email)->setTo($email);
            $this->container->get('mailer')->send($report);
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
