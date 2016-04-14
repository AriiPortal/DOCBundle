<?php

namespace Arii\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connections
 *
 * @ORM\Table(name="DOC_RULE")
 * @ORM\Entity(repositoryClass="Arii\DocBundle\Entity\RuleRepository")
 */
class Rule
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
    * @ORM\ManyToOne(targetEntity="Arii\DocBundle\Entity\Type")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=32, nullable=true )
     */        
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="preg_match", type="string", length=255, nullable=true )
     */        
    private $preg_match;
    

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
     * Set type
     *
     * @param string $type
     * @return Rule
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Rule
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
     * Set preg_match
     *
     * @param string $pregMatch
     * @return Rule
     */
    public function setPregMatch($pregMatch)
    {
        $this->preg_match = $pregMatch;

        return $this;
    }

    /**
     * Get preg_match
     *
     * @return string 
     */
    public function getPregMatch()
    {
        return $this->preg_match;
    }
}
