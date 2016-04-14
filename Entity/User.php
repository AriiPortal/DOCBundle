<?php

namespace Arii\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connections
 *
 * @ORM\Table(name="DOC_USER")
 * @ORM\Entity(repositoryClass="Arii\DocBundle\Entity\UserRepository")
 */
class User
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
    * @ORM\ManyToOne(targetEntity="Arii\DocBundle\Entity\Call")
    * @ORM\JoinColumn(nullable=true)
    */
    private $call;
    
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true )
     */        
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1024, nullable=true )
     */        
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="triggers", type="string", length=1024, nullable=true )
     */        
    private $triggers;
        
    /**
     * @var string
     *
     * @ORM\Column(name="constraints", type="string", length=1024, nullable=true )
     */        
    private $constraints;
        
    /**
     * @var string
     *
     * @ORM\Column(name="instructions", type="string", length=1024, nullable=true )
     */        
    private $instructions;
    
    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=128, nullable=true )
     */        
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="restart", type="boolean" )
     */        
    private $restart;

    /**
     * @var string
     *
     * @ORM\Column(name="validated", type="boolean" )
     */        
    private $validated;
    
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
     * Set title
     *
     * @param string $title
     * @return User
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
     * @return User
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
     * Set trigger
     *
     * @param string $trigger
     * @return User
     */
    public function setTrigger($trigger)
    {
        $this->trigger = $trigger;

        return $this;
    }

    /**
     * Get trigger
     *
     * @return string 
     */
    public function getTrigger()
    {
        return $this->trigger;
    }

    /**
     * Set constraints
     *
     * @param string $constraints
     * @return User
     */
    public function setConstraints($constraints)
    {
        $this->constraints = $constraints;

        return $this;
    }

    /**
     * Get constraints
     *
     * @return string 
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Set instructions
     *
     * @param string $instructions
     * @return User
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
     * Set author
     *
     * @param string $author
     * @return User
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
     * @return User
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
     * Set call
     *
     * @param \Arii\DocBundle\Entity\Call $call
     * @return User
     */
    public function setCall(\Arii\DocBundle\Entity\Call $call = null)
    {
        $this->call = $call;

        return $this;
    }

    /**
     * Get call
     *
     * @return \Arii\DocBundle\Entity\Call 
     */
    public function getCall()
    {
        return $this->call;
    }

    /**
     * Set triggers
     *
     * @param string $triggers
     * @return User
     */
    public function setTriggers($triggers)
    {
        $this->triggers = $triggers;

        return $this;
    }

    /**
     * Get triggers
     *
     * @return string 
     */
    public function getTriggers()
    {
        return $this->triggers;
    }

    /**
     * Set restart
     *
     * @param boolean $restart
     * @return User
     */
    public function setRestart($restart)
    {
        $this->restart = $restart;

        return $this;
    }

    /**
     * Get restart
     *
     * @return boolean 
     */
    public function getRestart()
    {
        return $this->restart;
    }

    /**
     * Set validated
     *
     * @param boolean $validated
     * @return User
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * Get validated
     *
     * @return boolean 
     */
    public function getValidated()
    {
        return $this->validated;
    }
}
