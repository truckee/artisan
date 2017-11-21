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
        $this->fixtures = $this->loadFixtures([]);
//        file_put_contents("G:\\Documents\\response.html", $this->client->getResponse()->getContent());
    }
    public function testNew()
    {
        $crawler = $this->client->request('GET', '/show/new');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add show")')->count());
        
        $form = $crawler->selectButton('Add show')->form();
        $form['show[show]'] = 'Artisan Show 2001';
        $form['show[lowest]'] = 1;
        $form['show[highest]'] = 100;
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

    public function testOneNameShow()
    {
        $crawler = $this->client->request('GET', '/show/new');

        $form = $crawler->selectButton('Add show')->form();
        $form['show[firstName]'] = 'Benny';
        $form['show[lastName]'] = 'Borko';
        $crawler = $this->client->submit($form);

        $crawler = $this->client->request('GET', '/show/new');

        $form = $crawler->selectButton('Add show')->form();
        $form['show[firstName]'] = 'Benny';
        $form['show[lastName]'] = 'Borko';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Show already exists")')->count());
    }
}
