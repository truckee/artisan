<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\ExistingArtistControllerTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * ExistingArtistControllerTest
 *
 */
class ExistingArtistControllerTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
            'AppBundle\DataFixtures\Test\OneArtistFixture',
        ]);
    }

    public function testAddExistingAristToShow()
    {
        $crawler = $this->client->request('GET', '/artist/existing');

        $this->assertEquals(1, $crawler->filter('html:contains("Benny")')->count());

        $form = $crawler->selectButton('Add artist(s)')->form();
        $form['show_artists[artists][0]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artist  added!")')->count());
    }

    public function testAllArtistsInShow()
    {
        $crawler = $this->client->request('GET', '/artist/existing');

        $this->assertEquals(1, $crawler->filter('html:contains("Benny")')->count());

        $form = $crawler->selectButton('Add artist(s)')->form();
        $form['show_artists[artists][0]']->tick();
        $crawler = $this->client->submit($form);

        $crawler = $this->client->request('GET', '/artist/existing');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("All artists are participating in the show")')->count());
    }
}
