<?php

namespace Ekyna\Bundle\CoreBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

/**
 * Interface ImageInterface
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
interface ImageInterface
{
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
     * Set file
     *
     * @param File $file
     * @return ImageInterface|$this
     */
    public function setFile(File $file = null);

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
     * @return ImageInterface|$this
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
     * @return ImageInterface|$this
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
     * Get image alt.
     * 
     * @return string
     */
    public function getAlt();

    /**
     * Get image last update datetime.
     * 
     * @return \DateTime
     */
    public function getUpdatedAt();
}
