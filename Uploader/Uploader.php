<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
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
    private $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function prepare(UploadableInterface $uploadable)
    {
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
                $this->filesystem->write(
                    $uploadable->getPath(),
                    file_get_contents($uploadable->getFile()->getPathname())
                );
                $uploadable->setFile(null);
            } elseif ($uploadable->hasOldPath()) {
                $this->filesystem->rename($uploadable->getOldPath(), $uploadable->getPath());
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
            if ($this->filesystem->has($oldPath)) {
                $this->filesystem->delete($oldPath);
            }
            $uploadable->setOldPath(null);
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
        } while ($this->filesystem->has($path));

        $uploadable->setPath($path);
    }
}
