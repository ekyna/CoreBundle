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
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class KernelEventSubscriber
 * @package Ekyna\Bundle\CoreBundle\EventListener
 * @author  Étienne Dauvergne <contact@ekyna.com>
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
                            ->createRedirectResponse($request, $response, 301);
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
                ->createRedirectResponse($request, $path);
            $event->setResponse($response);

            // Add flash
            if (0 < strlen($message = $exception->getMessage())) {
                $this->container
                    ->get('session')
                    ->getFlashBag()
                    ->add($exception->getMessageType(), $message);
            }

        } elseif ($exception instanceof HttpException) {

            // Don't send log about http exceptions.
            return;

        } elseif (!$this->container->getParameter('kernel.debug')) {

            $this->sendExceptionReport(FlattenException::create($exception));

        }
    }

    /**
     * Kernel terminate event handler.
     * - Sends the report about uncaught exception.
     */
    public function sendExceptionReport(FlattenException $exception)
    {
        if (!$this->container->has('swiftmailer.mailer.report_mailer')) {
            return;
        }

        $mailer = $this->container->get('swiftmailer.mailer.report_mailer');

        $template = new TemplateReference('EkynaCoreBundle', 'Exception', 'exception', 'html', 'twig');
        $code = $exception->getCode();
        $email = $this->container->getParameter('error_report_mail');
        $request = $this->container->get('request_stack')->getMasterRequest();

        $subject = sprintf('[%s] Error report', $request->getHost());
        $content = $this->container->get('twig')->render((string)$template, [
            'status_code'    => $code,
            'status_text'    => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
            'exception'      => $exception,
            'request'        => $request,
            'logger'         => null,
            'currentContent' => null,
        ]);

        $report = new \Swift_Message($subject, $content, 'text/html');
        $report->setFrom($email)->setTo($email);

        try {
            $mailer->send($report);
        } catch (\Swift_TransportException $e) {
            // In case transport has bad configuration.
            $stop = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }
}
