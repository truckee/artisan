<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\ExistingArtistControllerTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * ExistingArtistControllerTest
 *
 */
class ExistingArtistControllerTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\Test\UsersFixture',
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
            'AppBundle\DataFixtures\Test\TwoArtistFixture',
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

    public function testAddExistingAristToShow()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/artist/existing');

        $this->assertEquals(1, $crawler->filter('html:contains("Al")')->count());

        $form = $crawler->selectButton('Add artist(s)')->form();
        $form['show_artists[artists][0]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artist  added!")')->count());
    }

    public function testAllArtistsInShow()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/artist/existing');

        $this->assertEquals(1, $crawler->filter('html:contains("Al")')->count());

        $form = $crawler->selectButton('Add artist(s)')->form();
        $form['show_artists[artists][0]']->tick();
        $crawler = $this->client->submit($form);

        $crawler = $this->client->request('GET', '/artist/existing');

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("All artists are participating in the show")')->count());

        $crawler = $this->client->request('GET', '/reports/viewArtists');

         $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Borko, Benny")')->count());
    }

    public function testEditArtistSelect()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/artist/edit');

        $this->assertEquals(1, $crawler->filter('html:contains("Select artist for edit")')->count());
    }

    public function testEditArtist()
    {
        $crawler = $this->login();
        $crawler = $this->client->request('GET', '/artist/edit');
        $form = $crawler->selectButton('Select')->form();
        $form['select_artist[artist]']->select(1);
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("First name:")')->count());

        $form = $crawler->selectButton('Edit artist')->form();
        $form['artist[inShow]']->tick();
        $crawler = $this->client->submit($form);

        $this->assertEquals(1, $crawler->filter('html:contains("Benny Borko updated!")')->count());

        $crawler = $this->client->request('GET', '/reports/viewArtists');

        $this->assertGreaterThan(0,
            $crawler->filter('html:contains("Borko, Benny")')->count());

        $crawler = $this->client->request('GET', '/artist/edit');
        $form = $crawler->selectButton('Select')->form();
        $form['select_artist[artist]']->select(1);
        $crawler = $this->client->submit($form);
        $form = $crawler->selectButton('Edit artist')->form();
        $form['artist[inShow]']->untick();
        $crawler = $this->client->submit($form);
        $crawler = $this->client->request('GET', '/reports/viewArtists');
        
        $this->assertEquals(0,
            $crawler->filter('html:contains("Borko, Benny")')->count());
    }
}
