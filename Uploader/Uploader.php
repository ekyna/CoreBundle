<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Exception\UploadException;
use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
use League\Flysystem\MountManager;

/**
 * Class Uploader
 * @package Ekyna\Bundle\CoreBundle\Uploader
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Uploader implements UploaderInterface
{
    /**
     * @var MountManager
     */
    protected $mountManager;

    /**
     * @var string
     */
    protected $targetFileSystem;


    /**
     * Constructor.
     *
     * @param MountManager $mountManager
     * @param string       $targetFs
     */
    public function __construct(MountManager $mountManager, $targetFs = 'local_upload')
    {
        $this->mountManager      = $mountManager;
        $this->targetFileSystem  = $targetFs;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(UploadableInterface $uploadable)
    {
        if ($uploadable->hasFile() || $uploadable->hasKey() || $uploadable->shouldBeRenamed()) {
            $uploadable->setOldPath($uploadable->getPath());
            $uploadable->setPath($this->generatePath($uploadable->guessFilename()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function upload(UploadableInterface $uploadable)
    {
        if ($uploadable->hasPath()) {
            $targetKey = sprintf('%s://%s', $this->targetFileSystem, $uploadable->getPath());

            // By file
            if ($uploadable->hasFile()) {
                $file = $uploadable->getFile();
                if (false === $stream = fopen($file->getRealPath(), 'r+')) {
                    throw new UploadException($file->getRealPath());
                }

                $this->mountManager->writeStream($targetKey, $stream);

                fclose($stream);
                unlink($file->getRealPath());
                $uploadable->setFile(null);

            // By key
            } elseif ($uploadable->hasKey()) {
                if (!$this->mountManager->has($uploadable->getKey())) {
                    throw new UploadException($uploadable->getKey());
                }
                $this->mountManager->move($uploadable->getKey(), $targetKey);
                $uploadable->setKey(null);

            // Rename
            } elseif ($uploadable->hasOldPath()) {
                $sourceKey = sprintf('%s://%s', $this->targetFileSystem, $uploadable->getOldPath());
                $this->mountManager->rename($sourceKey, $targetKey);
            }
        }

        $this->remove($uploadable);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(UploadableInterface $uploadable)
    {
        if ($uploadable->hasOldPath()) {
            $targetKey = sprintf('%s://%s', $this->targetFileSystem, $uploadable->getOldPath());
            if ($this->mountManager->has($targetKey)) {
                $this->mountManager->delete($targetKey);
            }
            $uploadable->setOldPath(null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generatePath($filename)
    {
        do {
            $hash = md5(uniqid(mt_rand()));
            $path = sprintf(
                '%s/%s/%s',
                substr($hash, 0, 3),
                substr($hash, 3, 3),
                $filename
            );
        } while ($this->mountManager->has(sprintf('%s://%s', $this->targetFileSystem, $path)));

        return $path;
    }
}
