<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Dinosaur;
use AppBundle\Service\DinosaurLengthDeterminator;

class DinosaurFactory
{
    private $lengthDeterminator;

    public function __construct(DinosaurLengthDeterminator $lengthDeterminator)
    {
        $this->lengthDeterminator = $lengthDeterminator;
    }

    /**
     * @param integer $length
     * @return Dinosaur
     */
    public function growVelociraptor(int $length): Dinosaur
    {
        return $this->createDinosaur('Velociraptor', true, $length);
    }

    /**
     *  @param string $specification
     * @return Dinosaur
     */
    public function growFromSpecification(string $specification): Dinosaur
    {
        // Defaults

        $codeName = 'Ing-' . random_int(1, 99999);
        $length = $this->lengthDeterminator->getLengthFromSpecification($specification);
        $isCarnivorous = false;

        if (stripos($specification, 'carnivorous') !== false) {
            $isCarnivorous = true;
        }

        $dinosaur = $this->createDinosaur($codeName, $isCarnivorous, $length);

        return $dinosaur;
    }

    /**
     * @param string $genus
     * @param boolean $isCarnivorous
     * @param integer $length
     * @return Dinosaur
     */
    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);

        return $dinosaur;
    }
}