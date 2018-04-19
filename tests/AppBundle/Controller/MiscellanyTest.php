<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\MiscellanyTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * DeleteArtistTest
 *
 */
class MiscellanyTest extends WebTestCase
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
            'AppBundle\DataFixtures\Test\ShowFixture',
            ])->getReferenceRepository();
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

    public function testDeleteArtist()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/artist/delete');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Einstein, Al")')->count());

        $form = $crawler->filter('form')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artist Al Einstein deleted")')->count());
    }

    public function testViewSingleReceipt()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/viewSingleReceipt/1');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Borko, Benny")')->count());
    }

    public function testShowSummaryReport()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/reports/showSummary');
        $form = $crawler->filter('form')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Summary for Art 2018")')->count());
    }

    public function testAdminUsers()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/_admin');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Username")')->count());
    }
}
