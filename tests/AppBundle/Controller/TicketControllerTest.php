<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\TicketControllerTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * TicketControllerTest
 *
 */
class TicketControllerTest extends WebTestCase
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

    public function testSearchTicket()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/add');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add ticket")')->count());

        $crawler = $this->client->request('GET', '/ticket/add/1');
        $form = $crawler->filter('form')->form();
        $form['ticket[ticket]'] = 1;
        $form['ticket[amount]'] = 5;
        $crawler = $this->client->submit($form);

        $crawler = $this->client->request('GET', '/ticket/search');
        $form = $crawler->filter('form')->form();
        $form['ticket_search[ticket]'] = 1;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Borko, Benny")')->count());

        $crawler = $this->client->request('GET', '/ticket/search');
        $form = $crawler->filter('form')->form();
        $form['ticket_search[ticket]'] = 88;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Ticket 88 not found")')->count());
    }
    
}
