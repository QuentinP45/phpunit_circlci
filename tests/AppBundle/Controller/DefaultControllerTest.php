<?php

namespace Tests\AppBundle\Controller;

// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use AppBundle\DataFixtures\ORM\LoadBasicParkData;
use AppBundle\DataFixtures\ORM\LoadSecurityData;

class defaultControllerTest extends WebTestCase
{
    public function testEnclosuresAreShownOnTHeHomepage()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ]);

        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $table = $crawler->filter('.table-enclosures');

        $this->assertCount(3, $table->filter('tbody tr'));
    }

    public function testThatThereIsAnAlarmButtonWithoutSecurity()
    {
        $fixtures = $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ])->getReferenceRepository();

        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $enclosure = $fixtures->getReference('carnivorous-enclosure');
        $selector = sprintf('#enclosure-%s .button-alarm', $enclosure->getId());

        $this->assertGreaterThan(0, $crawler->filter($selector)->count());
    }

    public function testItGrowsADinosaurFromSpecification()
    {
        $this->loadFixtures([
            LoadBasicParkData::class,
            LoadSecurityData::class
        ]);

        $client = static::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Grow dinosaur')->form();
        $form['enclosure']->select(3);
        $form['specification']->setValue('large herbivore');

        $client->submit($form);

        $this->assertContains(
            'Grew a large herbivore in enclosure #3',
            $client->getResponse()->getContent()
        );
    }
}