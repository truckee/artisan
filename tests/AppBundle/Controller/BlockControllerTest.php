<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\BlockControllerTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * BlockControllerTest
 *
 */
class BlockControllerTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
            'AppBundle\DataFixtures\Test\BlocksToShowFixture',
        ]);
//        file_put_contents("G:\\Documents\\response.html", $this->client->getResponse()->getContent());
    }

    public function testBlockWithShow()
    {
        $crawler = $this->client->request('GET', '/block/new');
        $form = $crawler->selectButton('Add block')->form();
        $form['block[lower]'] = 5;
        $form['block[upper]'] = 15;
        $crawler = $this->client->submit($form);
file_put_contents("G:\\Documents\\response.html", $this->client->getResponse()->getContent());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Block exists or overlaps existing block")')->count());
    }

    public function testBlockWithShowNotDefault()
    {
        $crawler = $this->client->request('GET', '/block/new');
        $form = $crawler->selectButton('Add block')->form();
        $form['block[lower]'] = 25;
        $form['block[upper]'] = 34;
        $crawler = $this->client->submit($form);

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Block added")')->count());
    }
}
