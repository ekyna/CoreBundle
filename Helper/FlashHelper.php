<?php

namespace Ekyna\Bundle\CoreBundle\Helper;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FlashHelper
 * @package Ekyna\Bundle\CoreBundle\Helper
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FlashHelper
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * Constructor.
     *
     * @param Session             $session
     * @param TranslatorInterface $translator
     */
    public function __construct(Session $session, TranslatorInterface $translator)
    {
        $this->session = $session;
        $this->translator = $translator;
    }

    /**
     * Adds a flash message.
     *
     * @param string $type
     * @param string $message
     *
     * @return FlashHelper
     */
    public function add($type, $message)
    {
        $this->session->getFlashBag()->add($type, $message);

        return $this;
    }

    /**
     * Adds a translated flash message.
     *
     * @param string $type
     * @param string $id
     * @param array  $parameters
     * @param string $domain
     * @param string $locale
     *
     * @return FlashHelper
     */
    public function addTrans($type, $id, $parameters = [], $domain = null, $locale = null)
    {
        $message = $this->translator->trans($id, $parameters, $domain, $locale);

        return $this->add($type, $message);
    }
}
