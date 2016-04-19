<?php

namespace Arii\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connections
 *
 * @ORM\Table(name="DOC_NOTE")
 * @ORM\Entity(repositoryClass="Arii\DocBundle\Entity\NoteRepository")
 */
class Note
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
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=1024, nullable=true )
     */        
    private $note;
    
    /**
    * @ORM\ManyToMany(targetEntity="Arii\DocBundle\Entity\Part")
    * @ORM\JoinTable(name="DOC_NOTE_PART")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $part;

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
     * Constructor
     */
    public function __construct()
    {
        $this->part = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string $name
     * @return Note
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
     * @return Note
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
     * @return Note
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
     * Set note
     *
     * @param string $note
     * @return Note
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Note
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
     * @return Note
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
     * Add part
     *
     * @param \Arii\DocBundle\Entity\Part $part
     * @return Note
     */
    public function addPart(\Arii\DocBundle\Entity\Part $part)
    {
        $this->part[] = $part;

        return $this;
    }

    /**
     * Remove part
     *
     * @param \Arii\DocBundle\Entity\Part $part
     */
    public function removePart(\Arii\DocBundle\Entity\Part $part)
    {
        $this->part->removeElement($part);
    }

    /**
     * Get part
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPart()
    {
        return $this->part;
    }
}
