<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\ReportsControllerTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * ReportsControllerTest
 *
 */
class ReportsControllerTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\Test\UsersFixture',
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
            'AppBundle\DataFixtures\Test\TwoArtistFixture',
            'AppBundle\DataFixtures\Test\BlocksToShowFixture',
            'AppBundle\DataFixtures\Test\ReceiptFixture',
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

    public function testViewShowArtists()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/artistsInShow');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Benny")')->count());
    }

    public function testShowBlocksByArtist()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/blocksByArtist');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("10")')->count());
    }

    public function testViewShowReceipts()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/blocksByArtist');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("10")')->count());
    }

    public function testViewSingleArtistTickets()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/singleArtistTickets');
        $form = $crawler->selectButton('Select')->form();
        $form['select_artist[artist]']->select(1);
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Benny Borko: Tickets")')->count());
    }

    public function testViewArtists()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/viewArtists');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Borko, Benny")')->count());
    }

    public function testShowArtistByBlocks()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/viewArtists');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Borko, Benny")')->count());
    }
}
