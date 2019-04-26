<?php

namespace Tests\AppBundle\Factory;

use PHPUnit\Framework\TestCase;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Entity\Dinosaur;
use AppBundle\Service\DinosaurLengthDeterminator;

class DinosaurFactoryTest extends TestCase
{
    /**
     * @var DinosaurFactory
     */
    private $factory;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $lengthDeterminator;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->lengthDeterminator = $this->createMock(DinosaurLengthDeterminator::class);
        $this->factory = new DinosaurFactory($this->lengthDeterminator);
    }

    public function testItGrowsAVelociraptor()
    {
        //$factory = new DinosaurFactory;
        $dinosaur = $this->factory->growVelociraptor(5);
        $this->assertInstanceOf(Dinosaur::class, $dinosaur);
        $this->assertIsString('string', $dinosaur->getGenus());
        $this->assertSame('Velociraptor', $dinosaur->getGenus());
        $this->assertSame(5, $dinosaur->getLength());
    }

    // public function testItGrowsATriceratops()
    // {
    //     $this->markTestIncomplete('Wait for confirmation');
    // }

    // public function testItGrowsABabyVelociraptor()
    // {
    //     if (!class_exists(Nanny::class)) {
    //         $this->markTestSkipped('Pas de nounou !');
    //     }

    //     $dinosaur = $this->factory->growVelociraptor(1);
    //     $this->assertSame(1, $dinosaur->getLength());
    // }

    /**
     * @dataProvider getSpecificationTests
     */
    public function testItGrowsADinosaurFromSpecification(string $spec, bool $expectedIsCarnivorous)
    {
        $this->lengthDeterminator->expects($this->once())
            ->method('getLengthFromSpecification')
            ->with($spec)
            ->willReturn(20);

        $dinosaur = $this->factory->growFromSpecification($spec);

        $this->assertSame($expectedIsCarnivorous, $dinosaur->getIsCarnivorous());
        $this->assertSame(20, $dinosaur->getLength());
    }

    public function getSpecificationTests()
    {
        return [
            // specification, isLarge, isCarnivorous
            'SpecTest1' => ['large carnivorous dinosaur', true],
            'SpecTest2' => ['give me all the cookies !!', false],
            'SpecTest3' => ['large herbivore', false]
        ];
    }
}