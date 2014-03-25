<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Ekyna\Bundle\CoreBundle\Model\ImageInterface;

/**
 * AbstractImage
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
     * File uploaded
     * 
     * @var \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected $file;

    /**
     * Path
     * 
     * @var string
     */
    protected $path;

    /**
     * Name
     * 
     * @var string
     */
    protected $name;

    /**
     * Alternative text
     * 
     * @var string
     */
    protected $alt;

    /**
     * Creation date
     * 
     * @var \Datetime
     */
    protected $createdAt;

    /**
     * Update date
     * 
     * @var \Datetime
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
     * Image has file
     * 
     * @return boolean
     */
    public function hasFile()
    {
        return null !== $this->file;
    }

    /**
     * Get file
     * 
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return \Ekyna\Bundle\CoreBundle\Entity\AbstractImage
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        
        return $this;
    }

    /**
     * Image has path
     *
     * @return boolean
     */
    public function hasPath()
    {
        return null !== $this->path;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return \Ekyna\Bundle\CoreBundle\Entity\AbstractImage
     */
    public function setPath($path)
    {
        $this->path = $path;
        
        return $this;
    }

    /**
     * Image should be renamed
     *
     * @return boolean
     */
    public function shouldBeRenamed()
    {
        return (bool) ($this->hasPath() && $this->guessFilename() != pathinfo($this->getPath(), PATHINFO_BASENAME));
    }

    /**
     * Guess file name
     *
     * @return string
     */
    public function guessFilename()
    {
        // Extension
        $extension = null;
        if($this->hasFile()) {
            $extension = $this->file->guessExtension();
        }elseif($this->hasPath()) {
            $extension = pathinfo($this->getPath(), PATHINFO_EXTENSION);
        }
        
        // Filename
        $filename = null;
        if($this->hasName()) {
            $filename = pathinfo($this->name, PATHINFO_FILENAME);
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
     * Image has name
     * 
     * @return boolean
     */
    public function hasName()
    {
        return (bool) (1 === preg_match('/^[a-z0-9-]+\.(jpg|jpeg|gif|png)$/', $this->name));
    }

    /**
     * Get name
     * 
     * @return string
     */
    public function getName()
    {
        return $this->hasName() ? $this->name : $this->guessFilename();
    }

    /**
     * Set name
     * @param string $name
     * @return \Ekyna\Bundle\CoreBundle\Entity\AbstractImage
     */
    public function setName($name)
    {
        $this->name = $name;
        
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
     * @return \Ekyna\Bundle\CoreBundle\Entity\AbstractImage
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
        
        return $this;
    }

    /**
     * Get createdAt
     * 
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     * 
     * @param \Datetime $createdAt
     * @return \Ekyna\Bundle\CoreBundle\Entity\AbstractImage
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \Datetime $updated
     * @return \Ekyna\Bundle\CoreBundle\Entity\AbstractImage
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
}
