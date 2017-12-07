<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\Tests\AppBundle\Controller\BlockWithShowNoArtistTest.php

namespace Tests\AppBundle\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * BlockControllerTest
 *
 */
class BlockWithShowNoArtistTest extends WebTestCase
{

    public function setup()
    {
        $this->client = static::createClient();
        $this->client->followRedirects();
        $this->fixtures = $this->loadFixtures([
            'AppBundle\DataFixtures\Test\DefaultShowFixture',
        ]);
    }

    public function testBlockWithShowNoArtist()
    {
        $crawler = $this->client->request('GET', '/block/new');

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Select an artist before adding a ticket block!")')->count());
    }
}
