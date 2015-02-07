<?php

namespace Ekyna\Bundle\CoreBundle\Model;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait UploadableTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait UploadableTrait
{
    /**
     * File uploaded
     *
     * @var \Symfony\Component\HttpFoundation\File\File
     */
    protected $file;

    /**
     * Path
     *
     * @var string
     */
    protected $path;

    /**
     * Old path (to be removed)
     *
     * @var string
     */
    protected $oldPath;

    /**
     * Name
     *
     * @var string
     */
    protected $rename;

    /**
     * Update date
     *
     * @var \DateTime
     */
    protected $updatedAt;


    /**
     * Image has file.
     *
     * @return boolean
     */
    public function hasFile()
    {
        return null !== $this->file;
    }

    /**
     * Get file.
     *
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file
     *
     * @param File $file
     * @return UploadableTrait|$this
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;
        if (0 == strlen($this->rename)) {
            if ($file instanceof UploadedFile) {
                $this->rename = $file->getClientOriginalName();
            } elseif ($file instanceof File) {
                $this->rename = $file->getBasename();
            }
        }

        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * Image has path.
     *
     * @return boolean
     */
    public function hasPath()
    {
        return null !== $this->path;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param string $path
     * @return UploadableTrait|$this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Image has old path.
     *
     * @return boolean
     */
    public function hasOldPath()
    {
        return null !== $this->oldPath;
    }

    /**
     * Get old path.
     *
     * @return string
     */
    public function getOldPath()
    {
        return $this->oldPath;
    }

    /**
     * Set old path.
     *
     * @param string $oldPath
     * @return UploadableTrait|$this
     */
    public function setOldPath($oldPath)
    {
        $this->oldPath = $oldPath;

        return $this;
    }

    /**
     * Returns whether the image should be renamed or not.
     *
     * @return boolean
     */
    public function shouldBeRenamed()
    {
        return (bool) ($this->hasPath() && $this->guessFilename() != pathinfo($this->getPath(), PATHINFO_BASENAME));
    }

    /**
     * Guess file extension.
     *
     * @return string
     */
    public function guessExtension()
    {
        if($this->hasFile()) {
            return $this->file->guessExtension();
        }elseif($this->hasPath()) {
            return pathinfo($this->getPath(), PATHINFO_EXTENSION);
        }
        return null;
    }

    /**
     * Guess file name.
     *
     * @return string
     */
    public function guessFilename()
    {
        // Extension
        $extension = $this->guessExtension();

        // Filename
        $filename = null;
        if($this->hasRename()) {
            $filename = Urlizer::transliterate(pathinfo($this->rename, PATHINFO_FILENAME));
        }elseif($this->hasFile()) {
            $filename = pathinfo($this->file->getFilename(), PATHINFO_FILENAME);
        }elseif($this->hasPath()) {
            $filename = pathinfo($this->path, PATHINFO_FILENAME);
        }

        if($filename !== null && $extension !== null) {
            return $filename.'.'.$extension;
        }

        return null;
    }

    /**
     * Returns whether the image has a rename or not.
     *
     * @return boolean
     */
    public function hasRename()
    {
        return 0 < strlen($this->rename);
        //return (bool) (1 === preg_match('/^[a-z0-9-]+\.(jpg|jpeg|gif|png)$/', $this->rename));
    }

    /**
     * Get image rename.
     *
     * @return string
     */
    public function getRename()
    {
        return $this->hasRename() ? $this->rename : $this->guessFilename();
    }

    /**
     * Set rename.
     *
     * @param string $rename
     * @return UploadableTrait|$this
     */
    public function setRename($rename)
    {
        if($rename !== $this->rename) {
            $this->updatedAt = new \DateTime();
        }
        $this->rename = $rename;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return UploadableTrait|$this
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
