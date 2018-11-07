<?php

namespace Ekyna\Bundle\CoreBundle\Service\SwiftMailer;

use Psr\Log\LoggerInterface;
use Swift_Events_SendEvent;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ImapCopyPlugin
 * @package Ekyna\Bundle\CoreBundle\Service\SwiftMailer
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class ImapCopyPlugin implements \Swift_Events_SendListener, EventSubscriberInterface
{
    const HEADER = 'X-Imap-Copy';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var array
     */
    private $config;

    /**
     * @var resource
     */
    private $mailbox;


    /**
     * Constructor.
     *
     * @param LoggerInterface $logger
     * @param array $config
     */
    public function __construct(LoggerInterface $logger, array $config)
    {
        $this->logger = $logger;
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function beforeSendPerformed(Swift_Events_SendEvent $evt)
    {
    }

    /**
     * @inheritDoc
     */
    public function sendPerformed(Swift_Events_SendEvent $evt)
    {
        $pass = [\Swift_Events_SendEvent::RESULT_SUCCESS, \Swift_Events_SendEvent::RESULT_TENTATIVE];

        if (!in_array($evt->getResult(), $pass, true)) {
            return;
        }

        $message = $evt->getMessage();

        if (!$message->getHeaders()->has(static::HEADER)) {
            return;
        }

        $message->getHeaders()->remove(static::HEADER);

        if (false === $mailbox = $this->getMailbox()) {
            return;
        }

        $folder = mb_convert_encoding($this->config['folder'], "UTF7-IMAP", "UTF-8");

        if (!@imap_append($mailbox, $this->config['mailbox'].$folder, $message->toString(), '\Seen')) {
            $this->logger->error("IMAP message copy failed: "  . imap_last_error());
        }
    }

    /**
     * Kernel terminate event handler.
     */
    public function onKernelTerminate()
    {
        if (is_resource($this->mailbox)) {
            @imap_close($this->mailbox);
            $this->mailbox = null;
        }
    }

    /**
     * Returns the mailbox resource stream.
     *
     * @return resource|false
     */
    protected function getMailbox()
    {
        if (null !== $this->mailbox) {
            return $this->mailbox;
        }

        if (false === $this->mailbox = @imap_open($this->config['mailbox'], $this->config['user'], $this->config['password'])) {
            $this->logger->error("IMAP connection failed: " . imap_last_error());
        }

        return $this->mailbox;
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        $listeners = [
            KernelEvents::TERMINATE => ['onKernelTerminate', -1024], // After Symfony EmailSenderListener
        ];

        if (class_exists('Symfony\Component\Console\ConsoleEvents')) {
            $listeners[ConsoleEvents::TERMINATE] = ['onKernelTerminate', -1024]; // After Symfony EmailSenderListener
        }

        return $listeners;
    }
}
