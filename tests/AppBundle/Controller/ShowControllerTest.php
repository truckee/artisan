<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\ShowControllerTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * ShowControllerTest
 *
 */
class ShowControllerTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\Test\UsersFixture',
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
        ]);
    }

    public function login()
    {
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'manapw';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testShowEdit()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/show/edit');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('Select')->form();
        $form['select_show[show]']->select(1);
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Local tax rate")')->count());

        $form = $crawler->selectButton('Edit show')->form();
        $form['show[tax]'] = 8;
        $form['show[percent]'] = 20;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artisan Show 2017 updated")')->count());
    }

    public function testReceiptEdit()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/edit');
        
        $this->assertGreaterThan(0, $crawler->filter('html:contains("No tickets/blocks in active show")')->count());
    }
}
