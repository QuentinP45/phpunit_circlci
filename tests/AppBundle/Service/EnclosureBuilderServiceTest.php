<?php

namespace Tests\AppBundle\Service;

use PHPUnit\Framework\TestCase;
use AppBundle\Service\EnclosureBuilderService;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Factory\DinosaurFactory;
use AppBundle\Entity\Enclosure;

class EnclosureBuilderServiceTest extends TestCase
{
    public function testItBuildsAndPersistsEnclosure()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->once())
            ->method('persist')
            ->with($this->isInstanceOf(Enclosure::class));

        $em->expects($this->atLeast(1))
            ->method('flush');

        $dinoFactory = $this->createMock(DinosaurFactory::class);
        $dinoFactory->expects($this->exactly(2))
            ->method('growFromSpecification')
            ->with($this->isType('string'));

        $builder = new EnclosureBuilderService($em, $dinoFactory);
        $enclosure = $builder->buildEnclosure(1, 2);

        $this->assertCount(1, $enclosure->getSecurities());
        $this->assertCount(2, $enclosure->getDinosaurs());
    }
}