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
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
            'AppBundle\DataFixtures\Test\TwoArtistFixture',
            'AppBundle\DataFixtures\Test\BlocksToShowFixture',
        ]);
    }

    public function testFindExistingTicket()
    {
        $crawler = $this->client->request('GET', '/receipt/findTicket/1');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Benny")')->count());
    }

    public function testFindNotExistingTicket()
    {
        $crawler = $this->client->request('GET', '/receipt/findTicket/100');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Ticket does not exist")')->count());
    }

    public function testNewReceipt()
    {
        $crawler = $this->client->request('GET', '/receipt/new');
        $form = $crawler->selectButton('Add receipt')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("At least one ticket is required")')->count());
    }
}
