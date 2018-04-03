<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\ReceiptControllerTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * ReceiptControllerTest
 *
 */
class ReceiptControllerTest extends WebTestCase
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

    public function testFindExistingTicket()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/ticket/findTicket/1');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Benny")')->count());
    }

    public function testFindNotExistingTicket()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/ticket/findTicket/100');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Ticket does not exist")')->count());
    }
}
