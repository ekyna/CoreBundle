<?php

namespace Ekyna\Bundle\CoreBundle\Event;

use Ekyna\Bundle\CoreBundle\Modal\Modal;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ModalEvent
 * @package Ekyna\Bundle\CoreBundle\Event
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ModalEvent extends Event
{
    const MODAL_RESPONSE = 'ekyna_core.modal.response';


    /**
     * @var Modal
     */
    private $modal;


    /**
     * Constructor.
     *
     * @param Modal $modal
     */
    public function __construct(Modal $modal)
    {
        $this->modal = $modal;
    }

    /**
     * Returns the modal.
     *
     * @return Modal
     */
    public function getModal()
    {
        return $this->modal;
    }
}
