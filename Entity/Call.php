<?php

namespace Arii\DocBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connections
 *
 * @ORM\Table(name="DOC_CALL")
 * @ORM\Entity(repositoryClass="Arii\DocBundle\Entity\CallRepository")
 */
class Call
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
    * @ORM\ManyToOne(targetEntity="Arii\DocBundle\Entity\Part")
    * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
    */
    private $part;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true )
     */        
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="call_pgm", type="string", length=128, nullable=true )
     */        
    private $call_pgm;

    /**
     * @var string
     *
     * @ORM\Column(name="exit_code", type="string", length=32, nullable=true )
     */        
    private $exit_code;

    /**
     * @var string
     *
     * @ORM\Column(name="parms", type="string", length=255, nullable=true )
     */        
    private $parms;
 
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
     * Set message
     *
     * @param string $message
     * @return Call
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set call_pgm
     *
     * @param string $callPgm
     * @return Call
     */
    public function setCallPgm($callPgm)
    {
        $this->call_pgm = $callPgm;

        return $this;
    }

    /**
     * Get call_pgm
     *
     * @return string 
     */
    public function getCallPgm()
    {
        return $this->call_pgm;
    }

    /**
     * Set exit_code
     *
     * @param string $exitCode
     * @return Call
     */
    public function setExitCode($exitCode)
    {
        $this->exit_code = $exitCode;

        return $this;
    }

    /**
     * Get exit_code
     *
     * @return string 
     */
    public function getExitCode()
    {
        return $this->exit_code;
    }

    /**
     * Set parms
     *
     * @param string $parms
     * @return Call
     */
    public function setParms($parms)
    {
        $this->parms = $parms;

        return $this;
    }

    /**
     * Get parms
     *
     * @return string 
     */
    public function getParms()
    {
        return $this->parms;
    }

    /**
     * Set part
     *
     * @param \Arii\DocBundle\Entity\Part $part
     * @return Call
     */
    public function setPart(\Arii\DocBundle\Entity\Part $part = null)
    {
        $this->part = $part;

        return $this;
    }

    /**
     * Get part
     *
     * @return \Arii\DocBundle\Entity\Part 
     */
    public function getPart()
    {
        return $this->part;
    }
}
