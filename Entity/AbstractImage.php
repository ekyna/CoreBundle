<?php

namespace Ekyna\Bundle\CoreBundle\Entity;

use Ekyna\Bundle\CoreBundle\Model\ImageInterface;
use Gedmo\Sluggable\Util\Urlizer;
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
     * {@inheritdoc}
     */
    public function hasFile()
    {
        return null !== $this->file;
    }

    /**
     * {@inheritdoc}
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set file
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     * @return AbstractImage
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
        $this->updatedAt = new \DateTime();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasPath()
    {
        return null !== $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function hasOldPath()
    {
        return null !== $this->oldPath;
    }

    /**
     * {@inheritdoc}
     */
    public function getOldPath()
    {
        return $this->oldPath;
    }

    /**
     * {@inheritdoc}
     */
    public function setOldPath($oldPath)
    {
        $this->oldPath = $oldPath;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldBeRenamed()
    {
        return (bool) ($this->hasPath() && $this->guessFilename() != pathinfo($this->getPath(), PATHINFO_BASENAME));
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function guessFilename()
    {
        // Extension
        $extension = $this->guessExtension();

        // Filename
        $filename = null;
        if($this->hasName()) {
            $filename = Urlizer::transliterate(pathinfo($this->name, PATHINFO_FILENAME));
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
        return 0 < strlen($this->name);
        //return (bool) (1 === preg_match('/^[a-z0-9-]+\.(jpg|jpeg|gif|png)$/', $this->name));
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
     * 
     * @param string $name
     * @return AbstractImage
     */
    public function setName($name)
    {
        if($name !== $this->name) {
            $this->updatedAt = new \DateTime();
        }
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
     * @param \DateTime $updated
     * @return AbstractImage
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
