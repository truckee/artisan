<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\NoArtistInShowTest.php

namespace Tests\AppBundle;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * NoArtistInShowTest
 *
 */
class NoArtistInShowTest extends WebTestCase
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

    public function testBlockAdd()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/block/new');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No artists in active show")')->count());
    }

    public function testBlockEdit()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/block/edit');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No artists in active show")')->count());
    }

    public function testArtistsInShow()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/artistsInShow');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No artists in active show")')->count());
    }

    public function testBlocksByArtist()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/blocksByArtist');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No artists in active show")')->count());
    }

    public function testSingleArtistTicketst()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/singleArtistTickets');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No artists in active show")')->count());
    }

    public function testViewArtists()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/viewArtists');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No artists in active show")')->count());
    }

    public function testShowArtistByBlocks()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/ArtistsByBlock');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No artists in active show")')->count());
    }

    public function testViewReceipts()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/viewReceipts');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No receipts in active show")')->count());
    }

    public function testViewSingleReceipt()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/viewSingleReceipt');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No receipts in active show")')->count());
    }

}
