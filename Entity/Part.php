<?php

namespace Arii\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connections
 *
 * @ORM\Table(name="DOC_PART")
 * @ORM\Entity(repositoryClass="Arii\DocBundle\Entity\PartRepository")
 */
class Part
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
    * @ORM\ManyToOne(targetEntity="Arii\DocBundle\Entity\File")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="seq", type="integer", nullable=true )
     */        
    private $seq;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64, nullable=true )
     */        
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true )
     */        
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=1024, nullable=true )
     */        
    private $code;
    
    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="string", length=1024, nullable=true )
     */        
    private $comment;

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
     * Set part
     *
     * @param string $part
     * @return Part
     */
    public function setPart($part)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return string 
     */
    public function getPart()
    {
        return $this->part;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Part
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
     * Set instructions
     *
     * @param string $instructions
     * @return Part
     */
    public function setInstructions($instructions)
    {
        $this->instructions = $instructions;

        return $this;
    }

    /**
     * Get instructions
     *
     * @return string 
     */
    public function getInstructions()
    {
        return $this->instructions;
    }

    /**
     * Set file
     *
     * @param \Arii\DocBundle\Entity\File $file
     * @return Part
     */
    public function setFile(\Arii\DocBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \Arii\DocBundle\Entity\File 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Part
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
     * Set seq
     *
     * @param integer $seq
     * @return Part
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;

        return $this;
    }

    /**
     * Get seq
     *
     * @return integer 
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * Set code
     *
     * @param string $code
     * @return Part
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set comment
     *
     * @param string $comment
     * @return Part
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string 
     */
    public function getComment()
    {
        return $this->comment;
    }
}
