<?php

namespace Ekyna\Bundle\CoreBundle\EventListener;

use Ekyna\Bundle\CoreBundle\Exception\RedirectException;
use Ekyna\Bundle\CoreBundle\Redirection\ProviderRegistryInterface;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * @var ProviderRegistryInterface
     */
    private $registry;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var array
     */
    private $config;


    /**
     * Constructor.
     * 
     * @param Session                   $session
     * @param HttpUtils                 $httpUtils
     * @param ProviderRegistryInterface $registry
     * @param \Twig_Environment         $twig
     * @param \Swift_Mailer             $mailer
     * @param array                     $config
     */
    public function __construct(
        Session $session,
        HttpUtils $httpUtils,
        ProviderRegistryInterface $registry,
        \Twig_Environment $twig,
        \Swift_Mailer $mailer,
        array $config = array()
    ) {
        $this->session   = $session;
        $this->httpUtils = $httpUtils;
        $this->registry  = $registry;
        $this->twig      = $twig;
        $this->mailer    = $mailer;
        $this->config    = array_merge(array(
            'debug' => false,
            'email' => null,
        ), $config);
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
            foreach ($this->registry->getProviders() as $provider) {
                if ($provider->supports($request) && false !== $response = $provider->redirect($request)) {
                    if ($response instanceof RedirectResponse) {
                        $event->setResponse($response);
                    } elseif (is_string($response) && 0 < strlen($response)) {
                        $response = $this->httpUtils->createRedirectResponse($request, $response, 301);
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
            $response = $this->httpUtils->createRedirectResponse($request, $path);
            $event->setResponse($response);

            // Add flash
            if (0 < strlen($message = $exception->getMessage())) {
                $this->session->getFlashBag()->add($exception->getMessageType(), $message);
            }

        } elseif ($exception instanceof HttpException) {

            // Don't send log about http exception.
            return;

        } elseif(!$this->config['debug'] && 0 < strlen($email = $this->config['email'])) {

            $template = new TemplateReference('TwigBundle', 'Exception', 'exception', 'txt', 'twig');
            $code = $exception->getCode();

            $content = $this->twig->render(
                (string) $template,
                array(
                    'status_code' => $code,
                    'status_text' => isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '',
                    'exception' => FlattenException::create($exception),
                    'logger' => null,
                    'currentContent' => null,
                )
            );

            $report = \Swift_Message::newInstance('Error report', $content, 'text/plain');
            $report->setFrom($email)->setTo($email);
            $this->mailer->send($report);
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
