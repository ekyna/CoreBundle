<?php

namespace Ekyna\Bundle\CoreBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Interface UploadableInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface UploadableInterface extends TimestampableInterface
{
    /**
     * Sets the key.
     *
     * @param string $key
     * @return UploadableTrait
     */
    public function setKey($key);

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
     * Set file
     *
     * @param File $file
     * @return UploadableInterface|$this
     */
    public function setFile(File $file = null);

    /**
     * Image has file.
     *
     * @return boolean
     */
    public function hasFile();

    /**
     * Get file.
     *
     * @return File
     */
    public function getFile();

    /**
     * Sets the size.
     *
     * @param int $size
     * @return UploadableInterface|$this
     */
    public function setSize($size);

    /**
     * Returns the size.
     *
     * @return int
     */
    public function getSize();

    /**
     * Set path.
     *
     * @param string $path
     * @return UploadableInterface|$this
     */
    public function setPath($path);

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
     * Set old path.
     *
     * @param string $oldPath
     * @return UploadableInterface|$this
     */
    public function setOldPath($oldPath);

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
     * Set rename.
     *
     * @param string $rename
     * @return UploadableInterface|$this
     */
    public function setRename($rename);

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
     * Sets whether the uploadable should be unlinked from subject.
     *
     * @param boolean $unlink
     * @return UploadableTrait
     */
    public function setUnlink($unlink);

    /**
     * Returns whether the uploadable should be unlinked from subject.
     *
     * @return boolean
     */
    public function getUnlink();
}
