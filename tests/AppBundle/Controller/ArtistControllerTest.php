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

class ArtistControllerTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        //empty database
        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
        ]);
//        file_put_contents("G:\\Documents\\response.html", $this->client->getResponse()->getContent());
    }

    public function testNew()
    {
        $crawler = $this->client->request('GET', '/artist/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add artist")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artisan Show 2017")')->count());

        $form = $crawler->selectButton('Add artist')->form();
        $form['artist[firstName]'] = 'Benny';
        $form['artist[lastName]'] = 'Borko';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artist added")')->count());

        $crawler = $this->client->request('GET', '/artist/existing');

         $this->assertEquals(1, $crawler->filter('html:contains("Benny")')->count());
    }

    public function testValidation()
    {
        $crawler = $this->client->request('GET', '/artist/new');
        $form = $crawler->selectButton('Add artist')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("First name may not be empty")')->count());
    }

    public function testOneNameArtist()
    {
        $crawler = $this->client->request('GET', '/artist/new');

        $form = $crawler->selectButton('Add artist')->form();
        $form['artist[firstName]'] = 'Benny';
        $form['artist[lastName]'] = 'Borko';
        $crawler = $this->client->submit($form);

        $crawler = $this->client->request('GET', '/artist/new');

        $form = $crawler->selectButton('Add artist')->form();
        $form['artist[firstName]'] = 'Benny';
        $form['artist[lastName]'] = 'Borko';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artist already exists")')->count());
    }
}
