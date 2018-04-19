<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\ArtistInShowTest.php

namespace Tests\AppBundle;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * ArtistInShowTest
 *
 */
class ArtistInShowTest extends WebTestCase
{
    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
                'AppBundle\DataFixtures\Test\UsersFixture',
                'AppBundle\DataFixtures\Test\DefaultShowFixture',
                'AppBundle\DataFixtures\Test\OneArtistFixture',
        ]);
    }

    public function login()
    {
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = 'support';
        $form['_password'] = 'art2017';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testBlockEdit()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/block/edit');
        $form = $crawler->selectButton('Select')->form();
        $form['select_artist[artist]']->select(1);
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No tickets for this artist in active show")')->count());
    }

    public function testReceiptEdit()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/edit');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No tickets/blocks in active show")')->count());
    }

    public function testReceiptAdd()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/add');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No tickets/blocks in active show")')->count());
    }

    public function testBlocksByArtist()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/blocksByArtist');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No tickets/blocks in active show")')->count());
    }

    public function testArtistByBlock()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/blocksByArtist');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No tickets/blocks in active show")')->count());
    }

    public function testSingleArtistTicket()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/singleArtistTickets/1');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("No tickets for this artist in active show")')->count());
    }

}
