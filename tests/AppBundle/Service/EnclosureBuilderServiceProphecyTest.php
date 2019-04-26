<?php

namespace Tests\AppBundle\Service;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\Enclosure;
use Prophecy\Argument;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Service\EnclosureBuilderService;
use AppBundle\Entity\Dinosaur;

class EnclosureBuilderServiceProphecyTest extends TestCase
{
    public function testItBuildsAndPersistsEnclosure()
    {
        $em = $this->prophesize(EntityManagerInterface::class);
        $em->persist(Argument::type(Enclosure::class))
            ->shouldBeCalledTimes(1);

        $em->flush()->shouldBeCalled();

        $dinoFactory = $this->prophesize(DinosaurFactory::class);
        $dinoFactory->growFromSpecification(Argument::type('string'))
            ->shouldBeCalledTimes(2)
            ->willReturn(new Dinosaur);

        $builder = new EnclosureBuilderService($em->reveal(), $dinoFactory->reveal());
        $enclosure = $builder->buildEnclosure(1, 2);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(2, $enclosure->getDinosaurs());
    }
}