<?php

namespace Tests\AppBundle\Service;

use AppBundle\Entity\Dinosaur;
use PHPUnit\Framework\TestCase;
use AppBundle\Service\DinosaurLengthDeterminator;

class DinosaurLengthDeterminatorTest extends TestCase
{
    /**
     * @dataProvider getSpecLengthTests
     */
    public function testItReturnsCorrectLengthRange($spec, $minExpectedSize, $maxExpectedSize)
    {
        $determinator = new DinosaurLengthDeterminator;
        $actualSize = $determinator->getLengthFromSpecification($spec);
        
        $this->assertGreaterThanOrEqual($minExpectedSize, $actualSize);
        $this->assertLessThanOrEqual($maxExpectedSize, $actualSize);
    }

    public function getSpecLengthTests()
    {
        return [
            // specification, min length, max length
            ['large carnivorous dinosaur', Dinosaur::LARGE, Dinosaur::HUGE - 1],
            ['give me all the cookies !!', 0, Dinosaur::LARGE - 1],
            ['large herbivore', Dinosaur::LARGE, Dinosaur::HUGE - 1],
            ['very awesome huge', Dinosaur::HUGE, 100],
            ['very huge', Dinosaur::HUGE, 100]
        ];
    }
}