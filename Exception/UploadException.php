<?php

namespace Ekyna\Bundle\CoreBundle\Exception;

/**
 * Class UploadException
 * @package Ekyna\Bundle\CoreBundle\Exception
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class UploadException extends \Exception
{
    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('Failed to upload "%s".', $path));
    }
}
