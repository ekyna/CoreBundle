<?php

namespace Ekyna\Bundle\CoreBundle\Event;

use Ekyna\Bundle\CoreBundle\Model\FileInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class FileEvent
 * @package Ekyna\Bundle\CoreBundle\Event
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FileEvent extends Event
{
    /**
     * @var FileInterface
     */
    protected $file;

    /**
     * Constructor.
     *
     * @param FileInterface $file
     */
    public function __construct(FileInterface $file)
    {
        $this->file = $file;
    }

    /**
     * Returns the file.
     *
     * @return FileInterface
     */
    public function getFile()
    {
        return $this->file;
    }
}
