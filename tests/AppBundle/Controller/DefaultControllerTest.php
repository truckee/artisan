<?php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([]);
    }

    public function testIndex()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertContains('Welcome to Symfony', $crawler->filter('#container h1')->text());
    }

    public function testNoArtistWithoutDefaultShow()
    {
        $crawler = $this->client->request('GET', '/artist/new');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Create a default show before adding an artist")')->count()
        );
    }
}
