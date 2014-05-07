<?php

namespace Ekyna\Bundle\CoreBundle\Exception;

class RedirectException extends \Exception
{
    /**
     * The uri to redirect to.
     * 
     * @var string
     */
    private $uri;

    /**
     * The (flash) message type.
     * 
     * @var string
     */
    private $messageType = 'info';


    /**
     * Sets the redirect Uri.
     * 
     * @param unknown $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Returns the redirection Uri.
     * 
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Sets the (flash) message type.
     * 
     * @param string $type
     */
    public function setMessageType($type)
    {
        $this->type = $type;
    }

    /**
     * Returns the (flash) message type.
     */
    public function getMessageType()
    {
        return $this->type;
    }
}
