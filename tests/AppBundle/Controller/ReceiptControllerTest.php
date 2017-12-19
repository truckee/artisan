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
        $crawler = $this->client->request('GET', '/receipt/findTicket/1');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Benny")')->count());
    }

    public function testFindNotExistingTicket()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/findTicket/100');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Ticket does not exist")')->count());
    }

    public function testNewReceipt()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/new');
        $form = $crawler->selectButton('Add receipt')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("At least one ticket is required")')->count());
    }

    public function testNewReceiptWithTickets()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/receipt/new');
        $form = $crawler->selectButton('Add receipt')->form();
        $values = $form->getPhpValues();
        $values['receipt']['tickets'][0]['ticket'] = 1;
        $values['receipt']['tickets'][0]['amount'] = 50;
        $crawler = $this->client->request($form->getMethod(), $form->getUri(), $values, $form->getPhpFiles());

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Receipt added")')->count());

        $crawler = $this->client->request('GET', '/receipt/edit');
        $form = $crawler->selectButton('Edit')->form();
        $form['select_receipt[receipt]']->select(1125);
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("50")')->count());

        $crawler = $this->client->request('GET', '/receipt/view');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("$55")')->count());

        $crawler = $this->client->request('GET', '/artist/tickets');
        $form = $crawler->selectButton('Select')->form();
        $form['select_artist[artist]']->select(1);
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("$55")')->count());

    }
}
