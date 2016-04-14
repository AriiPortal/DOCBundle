<?php

namespace Arii\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connections
 *
 * @ORM\Table(name="DOC_FILE")
 * @ORM\Entity(repositoryClass="Arii\DocBundle\Entity\FileRepository")
 */
class File
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true )
     */        
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=true )
     */        
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=128, nullable=true )
     */        
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true )
     */        
    private $description;
    
    /**
    * @ORM\ManyToOne(targetEntity="Arii\DocBundle\Entity\Type")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=128, nullable=true )
     */        
    private $author;

    /**
     * @var datetime
     *
     * @ORM\Column(name="creation", type="datetime", nullable=true )
     */        
    private $creation;    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return File
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return File
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return File
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return File
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return File
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set creation
     *
     * @param \DateTime $creation
     * @return File
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * Get creation
     *
     * @return \DateTime 
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * Set type
     *
     * @param \Arii\DocBundle\Entity\Type $type
     * @return File
     */
    public function setType(\Arii\DocBundle\Entity\Type $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Arii\DocBundle\Entity\Type 
     */
    public function getType()
    {
        return $this->type;
    }
}
