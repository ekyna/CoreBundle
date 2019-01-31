<?php

namespace Ekyna\Bundle\CoreBundle\Modal;

use Ekyna\Bundle\CoreBundle\Event\ModalEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Renderer
 * @package Ekyna\Bundle\CoreBundle\Modal
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
class Renderer
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var array
     */
    protected $config;


    /**
     * Constructor.
     *
     * @param \Twig_Environment        $twig
     * @param TranslatorInterface      $translator
     * @param EventDispatcherInterface $dispatcher
     * @param array                    $config
     */
    public function __construct(
        \Twig_Environment $twig,
        TranslatorInterface $translator,
        EventDispatcherInterface $dispatcher,
        array $config
    ) {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->dispatcher = $dispatcher;

        $this->config = array_replace([
            'template' => '@EkynaCore/Modal/modal.xml.twig',
            'charset'  => 'UTF-8',
        ], $config);
    }

    /**
     * Renders and returns the modal response.
     *
     * @param Modal  $modal
     * @param string $template
     *
     * @return Response
     */
    public function render(Modal $modal, $template = null)
    {
        // Translations
        $modal->setTitle($this->translator->trans($modal->getTitle()));
        $buttons = $modal->getButtons();
        foreach ($buttons as &$button) {
            $button['label'] = $this->translator->trans($button['label']);
        }
        $modal->setButtons($buttons);

        // Event
        $this->dispatcher->dispatch(ModalEvent::MODAL_RESPONSE, new ModalEvent($modal));

        if (empty($template)) {
            $template = $this->config['template'];
        }

        $response = new Response();
        $response->setContent($this->twig->render($template, ['modal' => $modal]));

        $response->headers->set(
            'Content-Type',
            'application/xml; charset=' . strtolower($this->config['charset']),
            true
        );

        return $response;
    }
}
