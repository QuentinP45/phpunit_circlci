<?php

namespace Tests\AppBundle\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use AppBundle\Service\EnclosureBuilderService;
use AppBundle\Entity\Security;
use AppBundle\Entity\Dinosaur;
use AppBundle\Entity\Enclosure;
use AppBundle\Factory\DinosaurFactory;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

class EnclosureBuilderServiceIntegrationTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();

        $this->truncateEntities();
    }

    public function testItBuildsEnclosureWithDefaultSpecification()
    {
        // /** @var EnclosureBuilderService $enclosureBuilderService */
        // $enclosureBuilderService = self::$kernel->getContainer()
        //     ->get('test.' . 'AppBundle\Service\EnclosureBuilderService');

        $dinoFactory = $this->createMock(DinosaurFactory::class);
        $dinoFactory->expects($this->any())
            ->method('growFromSpecification')
            ->willReturnCallBack(function($spec) 
                {
                    return new Dinosaur;
                }
            );

        $enclosureBuilderService = new EnclosureBuilderService(
            $this->getEntityManager(),
            $dinoFactory
        );

        $enclosureBuilderService->BuildEnclosure();

        /** @var EntityManager $em */
        $em = $this->getEntityManager();

        $count = (int) $em->getRepository(Security::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(1, $count, 'Amount of security systems is not the same');

        $count = (int) $em->getRepository(Dinosaur::class)
            ->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $this->assertSame(3, $count, 'Amount of dinosaurs is not the same');
    }

    private function truncateEntities()
    {
        $purger = new ORMPurger($this->getEntityManager());
        $purger->purge();
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}

