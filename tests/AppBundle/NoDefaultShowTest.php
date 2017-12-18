<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\NoDefaultShowTest.php

namespace Tests\AppBundle;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class NoDefaultShowTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
                'AppBundle\DataFixtures\Test\UsersFixture',
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

    public function testNewShow()
    {
        $crawler = $this->login();
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
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/show/new');
        $form = $crawler->selectButton('Add show')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Show may not be empty")')->count());
    }

    public function testShowTaxValidation()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/show/new');
        $form = $crawler->selectButton('Add show')->form();
        $form['show[tax]'] = '110';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Must be between 0% and 100%")')->count());
    }

    public function testShowTPercentValidation()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/show/new');
        $form = $crawler->selectButton('Add show')->form();
        $form['show[percent]'] = '-5';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Must be between 0% and 100%")')->count());
    }

    public function testNoReceiptAddWithoutDefaultShow()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/new');

        $this->assertGreaterThan(
            0, $crawler->filter('html:contains("Set a show to active before adding a receipt")')->count()
        );
    }

    public function testNoReceiptEditWithoutDefaultShow()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/edit');

        $this->assertGreaterThan(
            0, $crawler->filter('html:contains("Set a show to active before adding a receipt")')->count()
        );
    }

    public function testBlockAdd()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/block/new');

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Set a show to active before adding a ticket block!")')->count());
    }

    public function testNoLoginDefaultShow()
    {
        $crawler = $this->client->request('GET', '/');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("UUFNN Artisan Show Management")')->count());
    }
}
