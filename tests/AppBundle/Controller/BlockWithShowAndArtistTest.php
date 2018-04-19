<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\BlockWithShowAndArtistTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * BlockWithShowAndArtistTest
 *
 */
class BlockWithShowAndArtistTest extends WebTestCase
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

    public function testEditBlock()
    {
        $crawler = $this->login();
        $artist = $this->fixtures->getReference('artist');
        $artistId = $artist->getId();
        $block = $this->fixtures->getReference('block');
        $blockId = $block->getId();
        $crawler = $this->client->request('GET', '/block/edit/' . $blockId);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Edit block for Benny")')->count());
    }

    public function testNewBlock()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/block/new');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Select artist for block")')->count());

        $form = $crawler->selectButton('Select')->form();
        $form['select_artist[artist]']->select(1);
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add block for Benny")')->count());

        $form = $crawler->selectButton('Save')->form();
        $form['block[lower]'] = 1000;
        $form['block[upper]'] = 1050;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Block added")')->count());

        $crawler = $this->client->request('GET', '/reports/ArtistsByBlock');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("1050")')->count());
    }

    public function testSelectAndEditBlock()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/block/edit');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Select artist for block edit")')->count());

        $form = $crawler->selectButton('Select')->form();
        $form['select_artist[artist]']->select(1);
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Select block for Benny")')->count());

        $form = $crawler->selectButton('Select')->form();
        $form['select_block[block]']->select(1);
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Edit block")')->count());

        $form = $crawler->selectButton('Save')->form();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Block updated")')->count());
    }

    public function testNewBlockForArtist()
    {
        $crawler = $this->login();
        $artist = $this->fixtures->getReference('artist');
        $artistId = $artist->getId();
        $crawler = $this->client->request('GET', '/block/new/' . $artistId);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Add block for Benny")')->count());
    }

    public function testOverlappingBlockForOtherArtist()
    {
        $crawler = $this->login();
        $artist = $this->fixtures->getReference('artist2');
        $artist2Id = $artist->getId();
        $crawler = $this->client->request('GET', '/block/new/' . $artist2Id);
        $form = $crawler->selectButton('Save')->form();
        $form['block[lower]'] = 5;
        $form['block[upper]'] = 15;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Block exists or overlaps existing block")')->count());
    }

    public function testOkToShrinkBlock()
    {
        $crawler = $this->login();
        $artist = $this->fixtures->getReference('artist');
        $artistId = $artist->getId();
        $block = $this->fixtures->getReference('block');
        $blockId = $block->getId();
        $crawler = $this->client->request('GET', '/block/edit/' . $blockId);
        $form = $crawler->selectButton('Save')->form();
        $form['block[lower]'] = 5;
        $form['block[upper]'] = 10;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Block updated")')->count());
    }

    public function testReceiptFormReturnsArtistByTicket()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/ticket/findTicket/1');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Benny Borko")')->count());
    }
}
