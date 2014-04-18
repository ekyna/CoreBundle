<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Gaufrette\Filesystem;

/**
 * ImageUploader
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ImageUploader implements ImageUploaderInterface
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
    public function prepare(ImageInterface $image)
    {
        if ($image->hasFile() || $image->shouldBeRenamed()) {
            $image->setOldPath($image->getPath());
            $this->generatePath($image);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function upload(ImageInterface $image)
    {
        if ($image->hasPath()) {
            if ($image->hasFile()) {
                $this->filesystem->write(
                    $image->getPath(),
                    file_get_contents($image->getFile()->getPathname())
                );
            } elseif ($image->hasOldPath()) {
                $this->filesystem->rename($image->getOldPath(), $image->getPath());
            }
        }

        $this->remove($image);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(ImageInterface $image)
    {
        if ($image->hasOldPath()) {
            if ($this->filesystem->has($image->getOldPath())) {
                $this->filesystem->delete($image->getOldPath());
            }
            // TODO: remove empty directory ?
            $image->setOldPath(null);
        }
    }

    /**
     * Generates a unique image path
     * 
     * @param ImageInterface $image
     */
    private function generatePath(ImageInterface $image)
    {
        $filename = $image->guessFilename();

        do {
            $hash = md5(uniqid(mt_rand()));
            $path = sprintf(
                '%s/%s/%s',
                substr($hash, 0, 3),
                substr($hash, 3, 3),
                $filename
            );
        } while ($this->filesystem->has($path));

        $image->setPath($path);
    }
}
