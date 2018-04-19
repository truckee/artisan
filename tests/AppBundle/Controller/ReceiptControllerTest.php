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
        $form['_username'] = 'support';
        $form['_password'] = 'art2017';
        $crawler = $this->client->submit($form);

        return $crawler;
    }

    public function testAddTicketToReceipt()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/add');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add ticket")')->count());

        $crawler = $this->client->request('GET', '/ticket/add/1');
        $form = $crawler->filter('form')->form();
        $form['ticket[ticket]'] = 1;
        $form['ticket[amount]'] = 5;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("5")')->count());
    }

    public function testReceiptTickets()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/add');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add ticket")')->count());

        $crawler = $this->client->request('GET', '/ticket/add/1');
        $form = $crawler->filter('form')->form();
        $form['ticket[ticket]'] = 1;
        $form['ticket[amount]'] = 5;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("5")')->count());

        $crawler = $this->client->request('GET', '/ticket/add/1');
        $form = $crawler->filter('form')->form();
        $form['ticket[ticket]'] = 2;
        $form['ticket[amount]'] = -5;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("-5")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("5")')->count());

        $crawler = $this->client->request('GET', '/reports/viewReceipts');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("$0.00")')->count());

        $crawler = $this->client->request('GET', '/ticket/edit/1');
        $form = $crawler->filter('form')->form();
        $form['ticket[amount]'] = -5;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("receipt total may not be < 0")')->count());

        $crawler = $this->client->request('GET', '/ticket/edit/1');
        $form = $crawler->filter('form')->form();
        $form['ticket[amount]'] = 15;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("15")')->count());

        $crawler = $this->client->request('GET', '/receipt/edit');
        $form = $crawler->filter('form')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("15")')->count());

        $crawler = $this->client->request('GET', '/nontax/addAmount/1');
        $form = $crawler->filter('form')->form();
        $form['nontaxable[amount]'] = '85';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("85")')->count());

        $crawler = $this->client->request('GET', '/nontax/editAmount/1');
        $form = $crawler->filter('form')->form();
        $form['nontaxable[amount]'] = '-185';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Must be > 0")')->count());

        $crawler = $this->client->request('GET', '/nontax/editAmount/1');
        $form = $crawler->filter('form')->form();
        $form['nontaxable[amount]'] = '185';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("185")')->count());

    }

    public function testNegativeReceipt()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/add');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add ticket")')->count());

        $crawler = $this->client->request('GET', '/ticket/add/1');
        $form = $crawler->filter('form')->form();
        $form['ticket[ticket]'] = 1;
        $form['ticket[amount]'] = -5;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("receipt total may not be < 0")')->count());
    }
}
