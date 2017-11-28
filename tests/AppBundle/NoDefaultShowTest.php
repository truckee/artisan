<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class NoDefaultShowTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        //empty database
        $this->fixtures = $this->loadFixtures([

            ]);
    }

    public function testNewShow()
    {
        $crawler = $this->client->request('GET', '/show/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add show")')->count());

        $form = $crawler->selectButton('Add show')->form();
        $form['show[show]'] = 'Artisan Show 2001';
        $form['show[default]'] = true;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Show added")')->count());
    }

    public function testShowValidation()
    {
        $crawler = $this->client->request('GET', '/show/new');
        $form = $crawler->selectButton('Add show')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Show may not be empty")')->count());
    }

    public function testNoArtistWithoutDefaultShow()
    {
        $crawler = $this->client->request('GET', '/artist/new');

        $this->assertGreaterThan(
            0,
            $crawler->filter('html:contains("Create a default show before adding an artist")')->count()
        );
    }

    public function testNewBlock()
    {
        $crawler = $this->client->request('GET', '/block/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testBlockLimits()
    {
        $crawler = $this->client->request('GET', '/block/new');
        $form = $crawler->selectButton('Add block')->form();
        $form['block[lower]'] = '10';
        $form['block[upper]'] = '1';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Block upper end must be greater than or equal to lower end")')->count());
    }

    public function testBlockBadNumbers()
    {
        $crawler = $this->client->request('GET', '/block/new');
        $form = $crawler->selectButton('Add block')->form();
        $form['block[lower]'] = 'a';
        $form['block[upper]'] = 1;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Must be > 0")')->count());
    }

    public function testBlockAdd()
    {
        $crawler = $this->client->request('GET', '/block/new');
        $form = $crawler->selectButton('Add block')->form();
        $form['block[lower]'] = 1;
        $form['block[upper]'] = 10;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Block added")')->count());

        $crawler = $this->client->request('GET', '/block/new');
        $form = $crawler->selectButton('Add block')->form();
        $form['block[lower]'] = 5;
        $form['block[upper]'] = 15;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Block exists or overlaps existing block")')->count());
    }
}
