<?php

namespace Ekyna\Bundle\CoreBundle\Model;

use Behat\Transliterator\Transliterator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait UploadableTrait
 * @package Ekyna\Bundle\CoreBundle\Model
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
trait UploadableTrait
{
    use TimestampableTrait;

    /**
     * The key for the upload filesystem
     *
     * @var string
     */
    protected $key;

    /**
     * File uploaded
     *
     * @var File
     */
    protected $file;

    /**
     * Size
     *
     * @var int
     */
    protected $size;

    /**
     * Path
     *
     * @var string
     */
    protected $path;

    /**
     * Old path (for removal)
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
     * Unlink (set the subject image field to null)
     *
     * @var bool
     */
    protected $unlink;


    /**
     * Sets the key.
     *
     * @param string $key
     * @return UploadableTrait
     */
    public function setKey($key)
    {
        $this->key = $key;

        if (0 < strlen($this->key) && !$this->hasRename()) {
            if ($this->hasPath()) {
                $this->rename = pathinfo($this->path, PATHINFO_BASENAME);
            } else {
                $this->rename = pathinfo($this->key, PATHINFO_BASENAME);
            }
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Returns whether the uploadable has an upload key.
     *
     * @return bool
     */
    public function hasKey()
    {
        return 0 < strlen($this->key);
    }

    /**
     * Returns the key.
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
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

        if (null !== $file && !$this->hasRename()) {
            if ($this->hasPath()) {
                $this->rename = pathinfo($this->path, PATHINFO_BASENAME);
            } elseif ($file instanceof UploadedFile) {
                $this->rename = $file->getClientOriginalName();
            } elseif ($file instanceof File) {
                $this->rename = $file->getBasename();
            }
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * Has file.
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
     * Sets the size.
     *
     * @param int $size
     * @return UploadableTrait|$this
     */
    public function setSize($size)
    {
        $this->size = intval($size);
        return $this;
    }

    /**
     * Returns the size.
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
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
     * Has path.
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
     * Has old path.
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
     * Returns whether the uploadable should be renamed or not.
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
        $extension = null;
        if ($this->hasFile()) {
            $extension = $this->file->guessExtension();
        } elseif ($this->hasKey()) {
            $extension = pathinfo($this->getKey(), PATHINFO_EXTENSION);
        } elseif ($this->hasPath()) {
            $extension = pathinfo($this->getPath(), PATHINFO_EXTENSION);
        }
        $extension = strtolower($extension);
        return $extension;
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
        if ($this->hasRename()) {
            $filename = Transliterator::urlize(pathinfo($this->rename, PATHINFO_FILENAME));
        } elseif ($this->hasFile()) {
            $filename = pathinfo($this->file->getFilename(), PATHINFO_FILENAME);
        } elseif ($this->hasKey()) {
            $filename = pathinfo($this->getKey(), PATHINFO_FILENAME);
        } elseif ($this->hasPath()) {
            $filename = pathinfo($this->path, PATHINFO_FILENAME);
        }

        if ($filename !== null && $extension !== null) {
            return $filename . '.' . $extension;
        }

        return null;
    }

    /**
     * GuessFilename alias.
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->guessFilename();
    }

    /**
     * Set rename.
     *
     * @param string $rename
     * @return UploadableTrait|$this
     */
    public function setRename($rename)
    {
        if ($rename !== $this->rename) {
            $this->updatedAt = new \DateTime();
        }
        $this->rename = $rename;

        return $this;
    }

    /**
     * Returns whether the uploadable has a rename or not.
     *
     * @return boolean
     */
    public function hasRename()
    {
        return 0 < strlen($this->rename);
    }

    /**
     * Get rename.
     *
     * @return string
     */
    public function getRename()
    {
        return $this->hasRename() ? $this->rename : $this->guessFilename();
    }

    /**
     * Sets the whether the uploadable should be unlinked from subject.
     *
     * @param boolean $unlink
     * @return UploadableTrait
     */
    public function setUnlink($unlink)
    {
        $this->unlink = (bool) $unlink;
        return $this;
    }

    /**
     * Returns whether the uploadable should be unlinked from subject.
     *
     * @return boolean
     */
    public function getUnlink()
    {
        return $this->unlink;
    }
}
