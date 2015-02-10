<?php

namespace Ekyna\Bundle\CoreBundle\Exception;

/**
 * Class RedirectException
 * @package Ekyna\Bundle\CoreBundle\Exception
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class RedirectException extends \Exception
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $messageType;


    /**
     * Constructor.
     *
     * @param string $path        The path to redirect to (an absolute path (/foo), an absolute URL (http://...), or a route name (foo)).
     * @param int    $message     The (flash) message.
     * @param string $messageType The (flash) message type.
     */
    public function __construct($path, $message = null, $messageType = 'info')
    {
        parent::__construct($message);

        $this->path = $path;
        $this->messageType = $messageType;
    }

    /**
     * Sets the path.
     *
     * @param string $path
     * @return RedirectException
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Returns the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets the (flash) message type.
     * 
     * @param string $type
     * @return RedirectException
     */
    public function setMessageType($type)
    {
        $this->messageType = $type;
        return $this;
    }

    /**
     * Returns the (flash) message type.
     *
     * @return string
     */
    public function getMessageType()
    {
        return $this->messageType;
    }
}
