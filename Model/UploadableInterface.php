<?php

namespace Ekyna\Bundle\CoreBundle\Model;

use Symfony\Component\HttpFoundation\File\File as SFile;
use Gaufrette\File as GFile;

/**
 * Interface UploadableInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UploadableInterface
{
    /**
     * Returns whether the uploadable has an upload key.
     *
     * @return bool
     */
    public function hasKey();

    /**
     * Returns the key.
     *
     * @return string
     */
    public function getKey();

    /**
     * Sets the key.
     *
     * @param string $key
     * @return UploadableTrait
     */
    public function setKey($key);

    /**
     * Image has file.
     *
     * @return boolean
     */
    public function hasFile();

    /**
     * Get file.
     *
     * @return SFile|GFile
     */
    public function getFile();

    /**
     * Set file
     *
     * @param SFile|GFile $file
     * @return UploadableInterface|$this
     */
    public function setFile($file = null);

    /**
     * Image has path.
     *
     * @return boolean
     */
    public function hasPath();

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath();

    /**
     * Set path.
     *
     * @param string $path
     * @return UploadableInterface|$this
     */
    public function setPath($path);

    /**
     * Image has old path.
     *
     * @return boolean
     */
    public function hasOldPath();

    /**
     * Get old path.
     *
     * @return string
     */
    public function getOldPath();

    /**
     * Set old path.
     *
     * @param string $oldPath
     * @return UploadableInterface|$this
     */
    public function setOldPath($oldPath);

    /**
     * Returns whether the image should be renamed or not.
     *
     * @return boolean
     */
    public function shouldBeRenamed();

    /**
     * Guess file extension.
     *
     * @return string
     */
    public function guessExtension();

    /**
     * Guess file name.
     *
     * @return string
     */
    public function guessFilename();

    /**
     * Returns whether the image has a name or not.
     *
     * @return boolean
     */
    public function hasRename();

    /**
     * Get image rename.
     *
     * @return string
     */
    public function getRename();

    /**
     * Set rename.
     *
     * @param string $rename
     * @return UploadableInterface|$this
     */
    public function setRename($rename);
}
