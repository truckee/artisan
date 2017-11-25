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

class ShowControllerTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        //empty database
        $this->fixtures = $this->loadFixtures([
                'AppBundle\DataFixtures\Test\BlockFixture',
            ])->getReferenceRepository();
//        file_put_contents("G:\\Documents\\response.html", $this->client->getResponse()->getContent());
    }

    public function testNew()
    {
        $crawler = $this->client->request('GET', '/show/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add show")')->count());

        $form = $crawler->selectButton('Add show')->form();
        $form['show[show]'] = 'Artisan Show 2001';
        $form['show[start]'] = 1;
        $form['show[size]'] = 10;
        $form['show[default]'] = true;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Show added")')->count());
    }

    public function testValidation()
    {
        $crawler = $this->client->request('GET', '/show/new');
        $form = $crawler->selectButton('Add show')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Show may not be empty")')->count());
    }

    public function testFixture()
    {
        $show = $this->fixtures->getReference('show');
        $name = $show->getShow();

        $this->assertEquals($name, 'Artisan Show 2017');
    }

    public function testArtistFound()
    {
        $crawler = $this->client->request('GET', '/receipt/findTicket');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Find ticket")')->count());

        $form = $crawler->selectButton('Find ticket')->form();
        $form['receipt[ticketnumber]'] = 4;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Benny Borko")')->count());
    }

    public function testArtistNotFound()
    {
        $crawler = $this->client->request('GET', '/receipt/findTicket');
        $form = $crawler->selectButton('Find ticket')->form();
        $form['receipt[ticketnumber]'] = 44;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Ticket not found")')->count());
    }
}
