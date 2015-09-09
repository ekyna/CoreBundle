<?php

namespace Ekyna\Bundle\CoreBundle\Uploader;

use Ekyna\Bundle\CoreBundle\Exception\UploadException;
use Ekyna\Bundle\CoreBundle\Model\UploadableInterface;
use League\Flysystem\Adapter\AbstractFtpAdapter;
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

            // Check source and set size
            // By File
            if ($uploadable->hasFile()) {
                $file = $uploadable->getFile();

                if (!file_exists($file->getRealPath())) {
                    throw new UploadException(sprintf('Source file "%s" does not exists.', $file->getRealPath()));
                }

                $uploadable->setSize(filesize($file->getRealPath()));

            // By Key
            } elseif ($uploadable->hasKey()) {
                $sourceKey = $uploadable->getKey();

                if (!$this->checkKey($sourceKey)) {
                    throw new UploadException(sprintf('Source file "%s" does not exists.', $sourceKey));
                }

                $uploadable->setSize($this->mountManager->getSize($sourceKey));
            }
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
                    throw new UploadException(sprintf('Failed to open file "%s".', $file->getRealPath()));
                }

                if (!$this->mountManager->writeStream($targetKey, $stream)) {
                    throw new UploadException(sprintf('Failed to copy file from "%s" to "%s".', $file->getRealPath(), $targetKey));
                }

                fclose($stream);
                unlink($file->getRealPath());

                $uploadable->setFile(null);

            // By key
            } elseif ($uploadable->hasKey()) {
                $sourceKey = $uploadable->getKey();

                if (!$this->moveKey($sourceKey, $targetKey)) {
                    throw new UploadException(sprintf('Failed to copy file from "%s" to "%s".', $sourceKey, $targetKey));
                }

                $uploadable->setKey(null);

            // Rename
            } elseif ($uploadable->hasOldPath()) {
                $fs = $this->getFilesystem();

                if (!$fs->rename($uploadable->getOldPath(), $uploadable->getPath())) {
                    throw new UploadException(sprintf('Failed to rename file from "%s" to "%s".', $uploadable->getOldPath(), $uploadable->getPath()));
                }

                $this->cleanUp($uploadable->getOldPath());
                $uploadable->setOldPath(null);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function remove(UploadableInterface $uploadable)
    {
        if (0 < strlen($path = $uploadable->getOldPath())) {
            $fs = $this->getFilesystem();

            if (!$fs->has($path)) {
                throw new UploadException(sprintf('File "%s" not found.', $path));
            }
            if (!$fs->delete($path)) {
                throw new UploadException(sprintf('Failed to delete file "%s".', $path));
            }

            $this->cleanUp($path);
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

    /**
     * Removes the path directories if they are empty.
     *
     * @param string $path
     * @throws UploadException
     */
    private function cleanUp($path)
    {
        $fs = $this->getFilesystem();
        $parts = explode('/', $path);

        while (0 < count($parts)) {
            $key = implode('/', $parts);
            if ($fs->has($key)) {
                $dir = $fs->get($key);
                if (!$dir->isDir() || 0 < count($fs->listContents($key))) {
                    break;
                }
                if (!$fs->deleteDir($key)) {
                    throw new UploadException(sprintf('Failed to delete directory "%s".', $key));
                }
            }
            array_pop($parts);
        }
    }

    /**
     * Returns the target filesystem.
     *
     * @return \League\Flysystem\FilesystemInterface
     */
    private function getFilesystem()
    {
        return $this->mountManager->getFilesystem($this->targetFileSystem);
    }

    /**
     * Check the distant source key.
     *
     * @param string $sourceKey
     * @return bool
     */
    private function checkKey($sourceKey)
    {
        if ($this->mountManager->has($sourceKey)) {
            return true;
        }

        if ($this->reconnectDistantFs($sourceKey)) {
            if ($this->mountManager->has($sourceKey)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Copies or moves the distant source key to target key.
     *
     * @param string $sourceKey
     * @param string $targetKey
     * @return bool
     */
    private function moveKey($sourceKey, $targetKey)
    {
        if (!$this->isDistant($sourceKey)) {
            return $this->mountManager->move($sourceKey, $targetKey);
        }

        if ($this->mountManager->copy($sourceKey, $targetKey)) {
            return true;
        }
        if ($this->reconnectDistantFs($sourceKey)) {
            if ($this->mountManager->copy($sourceKey, $targetKey)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Reconnects the file system adapter if possible.
     *
     * @param string $key
     * @return bool
     */
    private function reconnectDistantFs($key)
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        list($prefix, $args) = $this->mountManager->filterPrefix([$key]);

        /** @var \League\Flysystem\FileSystem $fs */
        $fs = $this->mountManager->getFilesystem($prefix);
        $adapter = $fs->getAdapter();

        // Try reconnection
        if (!$adapter instanceof AbstractFtpAdapter) {
            return false;
        }

        $adapter->disconnect();
        sleep(1);
        $adapter->connect();

        return true;
    }

    /**
     * Returns whether the key is distant.
     *
     * @param string $key
     * @return bool
     */
    private function isDistant($key)
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        list($prefix, $args) = $this->mountManager->filterPrefix([$key]);

        /** @var \League\Flysystem\FileSystem $fs */
        $fs = $this->mountManager->getFilesystem($prefix);
        $adapter = $fs->getAdapter();

        // Try reconnection
        if ($adapter instanceof AbstractFtpAdapter) {
            return true;
        }

        return false;
    }
}
