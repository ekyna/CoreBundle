<?php

namespace Ekyna\Bundle\CoreBundle\Service\Asset;

use Symfony\Component\Asset\VersionStrategy\VersionStrategyInterface;

/**
 * Class FolderVersionStrategy
 * @package Ekyna\Bundle\CoreBundle\Service\Asset
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class FolderVersionStrategy implements VersionStrategyInterface
{
    /**
     * @var string
     */
    private $version;


    /**
     * Constructor.
     *
     * @param string $version
     */
    public function __construct(string $version)
    {
        $this->version = $version;
    }

    /**
     * @inheritDoc
     */
    public function getVersion($path)
    {
        return $this->version;
    }

    /**
     * @inheritDoc
     */
    public function applyVersion($path)
    {
        if ($path && '/' === $path[0]) {
            return $path;
        }

        return sprintf("%s/%s", $this->getVersion($path), ltrim($path, '/'));
    }
}
