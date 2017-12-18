<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\LoginTest.php

namespace Tests\AppBundle;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * LoginTest
 *
 */
class LoginTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\Test\UsersFixture',
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
        ]);
    }

    public function testAdminLogin()
    {
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = 'admin';
        $form['_password'] = 'manapw';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artisan Show 2017")')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Admin")')->count());
    }

    public function testUserLogin()
    {
        $crawler = $this->client->request('GET', '/');
        $form = $crawler->selectButton('Log in')->form();
        $form['_username'] = 'dberry';
        $form['_password'] = 'password';
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Artisan Show 2017")')->count());
        $this->assertEquals(0, $crawler->filter('html:contains("Admin")')->count());
    }
    
}
