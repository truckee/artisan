<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\ReplaceBlockOwnerTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * ReplaceBlockOwnerTest
 *
 */
class ReplaceBlockOwnerTest extends WebTestCase
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

    public function testBlockReassign()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/block/reassign');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Borko, Benny")')->count());

        $form = $crawler->filter('form')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("1 to 10")')->count());

        $form = $crawler->filter('form')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Einstein, Al")')->count());

        $form = $crawler->filter('form')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("tickets reassigned to artist")')->count());

    }
    
}
