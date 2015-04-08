<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
use Gaufrette\File;
use Gaufrette\Filesystem;

/**
 * Class Uploader
 * @package Ekyna\Bundle\CoreBundle\Uploader
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Uploader implements UploaderInterface
{
    /**
     * @var \Gaufrette\Filesystem
     */
    private $sourceFilesystem;

    /**
     * @var \Gaufrette\Filesystem
     */
    private $targetFilesystem;


    /**
     * @param Filesystem $filesystem : The source (upload) filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->sourceFilesystem = $filesystem;
    }

    /**
     * Sets the target filesystem.
     *
     * @param Filesystem $filesystem
     * @return Uploader
     */
    public function setTargetFilesystem(Filesystem $filesystem)
    {
        $this->targetFilesystem = $filesystem;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(UploadableInterface $uploadable)
    {
        if (null !== $this->sourceFilesystem && $uploadable->hasKey()) {
            if ($this->sourceFilesystem->has($uploadable->getKey())) {
                $uploadable->setFile($this->sourceFilesystem->get($uploadable->getKey()));
            }
        }

        if ($uploadable->hasFile() || $uploadable->shouldBeRenamed()) {
            $uploadable->setOldPath($uploadable->getPath());
            $this->generatePath($uploadable);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function upload(UploadableInterface $uploadable)
    {
        if ($uploadable->hasPath()) {
            if ($uploadable->hasFile()) {
                $file = $uploadable->getFile();
                $content = $file instanceof File ? $file->getContent() : file_get_contents($file->getPathname());
                $this->targetFilesystem->write($uploadable->getPath(), $content);
                $uploadable->setFile(null);
            } elseif ($uploadable->hasOldPath()) {
                $this->targetFilesystem->rename($uploadable->getOldPath(), $uploadable->getPath());
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
            $oldPath = $uploadable->getOldPath();
            if ($this->targetFilesystem->has($oldPath)) {
                $this->targetFilesystem->delete($oldPath);
            }
            $uploadable->setOldPath(null);
        }
        if (null !== $this->sourceFilesystem && $uploadable->hasKey()) {
            if ($this->sourceFilesystem->has($uploadable->getKey())) {
                $this->sourceFilesystem->delete($uploadable->getKey());
            }
            $uploadable->setKey(null);
        }
    }

    /**
     * Generates a unique path.
     * 
     * @param UploadableInterface $uploadable
     */
    private function generatePath(UploadableInterface $uploadable)
    {
        $filename = $uploadable->guessFilename();

        do {
            $hash = md5(uniqid(mt_rand()));
            $path = sprintf(
                '%s/%s/%s',
                substr($hash, 0, 3),
                substr($hash, 3, 3),
                $filename
            );
        } while ($this->targetFilesystem->has($path));

        $uploadable->setPath($path);
    }
}
