<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Enclosure;

/**
 * @ORM\Entity
 * @ORM\Table(name="dinosaurs")
 */
class Dinosaur
{
    const LARGE = 10;
    const HUGE = 30;
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $length = 0;
    private $genus;
    private $isCarnivorous;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Enclosure", inversedBy="dinosaurs")
     */
    private $enclosure;

    /**
     * Undocumented function
     *
     * @param string $genus
     * @param boolean $isCarnivorous
     */
    public function __construct(string $genus = 'Unknown', bool $isCarnivorous = false)
    {
        $this->genus = $genus;
        $this->isCarnivorous = $isCarnivorous;
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param integer $length
     * @return void
     */
    public function setLength(int $length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * @return string
     */
    public function getSpecification(): string
    {
        return sprintf(
            'The %s %scarnivorous dinosaur is %d meters long',
            $this->getGenus(),
            $this->getIsCarnivorous() ? '' : 'non-',
            $this->getLength()
        );
    }

    /**
     * Get the value of genus
     */ 
    public function getGenus()
    {
        return $this->genus;
    }

    /**
     * Set the value of genus
     *
     * @return  self
     */ 
    public function setGenus($genus)
    {
        $this->genus = $genus;

        return $this;
    }

    /**
     * Get the value of isCarnivorous
     */ 
    public function getIsCarnivorous()
    {
        return $this->isCarnivorous;
    }

    /**
     * Set the value of isCarnivorous
     *
     * @return  self
     */ 
    public function setIsCarnivorous($isCarnivorous)
    {
        $this->isCarnivorous = $isCarnivorous;

        return $this;
    }

    public function setEnclosure(Enclosure $enclosure)
    {
        $this->enclosure = $enclosure;
    }
}
