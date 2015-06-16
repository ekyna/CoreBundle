<?php

namespace Ekyna\Bundle\CoreBundle\Modal;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class Renderer
 * @package Ekyna\Bundle\CoreBundle\Modal
 * @author Étienne Dauvergne <contact@ekyna.com>
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
     * @var string
     */
    protected $charset;

    /**
     * Constructor.
     *
     * @param \Twig_Environment   $twig
     * @param TranslatorInterface $translator
     * @param string              $charset
     */
    public function __construct(\Twig_Environment $twig, TranslatorInterface $translator, $charset)
    {
        $this->twig       = $twig;
        $this->translator = $translator;
        $this->charset    = $charset;
    }

    /**
     * Renders and returns the modal response.
     *
     * @param Modal $modal
     * @param string $template
     * @return Response
     */
    public function render(Modal $modal, $template = 'EkynaCoreBundle:Modal:modal.xml.twig')
    {
        $response = new Response();

        // Translations
        $modal->setTitle($this->translator->trans($modal->getTitle()));
        $buttons = $modal->getButtons();
        foreach($buttons as &$button) {
            $button['label'] = $this->translator->trans($button['label']);
        }
        $modal->setButtons($buttons);

        $response->setContent($this->twig->render($template, array('modal' => $modal)));

        $response->headers->add(array('Content-Type' => 'application/xml; charset='.strtolower($this->charset)));

        return $response;
    }
}
