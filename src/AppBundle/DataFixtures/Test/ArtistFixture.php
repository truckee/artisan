<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\DataFixtures\Test\ArtistFixture.php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * ArtistFixture
 *
 */
class ArtistFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $manager->flush();
    }
}
