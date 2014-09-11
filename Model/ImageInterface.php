<?php

namespace Ekyna\Bundle\CoreBundle\Model;

use Symfony\Component\HttpFoundation\File\File;

/**
 * ImageInterface
 *
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
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getFile();

    /**
     * Set file
     *
     * @param \Symfony\Component\HttpFoundation\File\File $file
     * @return \Ekyna\Bundle\CoreBundle\Entity\AbstractImage
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
    public function hasName();

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
