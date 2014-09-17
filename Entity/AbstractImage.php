<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * AbstractImage
 *
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractImage implements ImageInterface
{
    /**
     * Id
     * 
     * @var integer
     */
    protected $id;

    /**
     * File
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
     * Rename
     * 
     * @var string
     */
    protected $rename;

    /**
     * Alternative text
     * 
     * @var string
     */
    protected $alt;

    /**
     * Creation date
     * 
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * Update date
     * 
     * @var \DateTime
     */
    protected $updatedAt;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * Get id
     * 
     * @return number
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritDoc}
     */
    public function hasFile()
    {
        return null !== $this->file;
    }

    /**
     * {@inheritDoc}
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function hasPath()
    {
        return null !== $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritDoc}
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasOldPath()
    {
        return null !== $this->oldPath;
    }

    /**
     * {@inheritDoc}
     */
    public function getOldPath()
    {
        return $this->oldPath;
    }

    /**
     * {@inheritDoc}
     */
    public function setOldPath($oldPath)
    {
        $this->oldPath = $oldPath;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function shouldBeRenamed()
    {
        return (bool) ($this->hasPath() && $this->guessFilename() != pathinfo($this->getPath(), PATHINFO_BASENAME));
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * Image has rename
     * 
     * @return boolean
     */
    public function hasRename()
    {
        return 0 < strlen($this->rename);
        //return (bool) (1 === preg_match('/^[a-z0-9-]+\.(jpg|jpeg|gif|png)$/', $this->rename));
    }

    /**
     * Get rename
     * 
     * @return string
     */
    public function getRename()
    {
        return $this->hasRename() ? $this->rename : $this->guessFilename();
    }

    /**
     * Set rename
     * 
     * @param string $rename
     * @return AbstractImage
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
     * Get alternative text
     * 
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set alternative text
     * 
     * @param string $alt
     * @return AbstractImage
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        
        return $this;
    }

    /**
     * Get createdAt
     * 
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     * 
     * @param \DateTime $createdAt
     * @return AbstractImage
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
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
     * @return AbstractImage
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
