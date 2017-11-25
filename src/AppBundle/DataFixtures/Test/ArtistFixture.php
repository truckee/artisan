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

use AppBundle\Entity\Show;
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
        $show = new Show();
        $show->setShow('Artisan Show 2017');
        $show->setStart(1);
        $show->setSize(10);
        $show->setDefault(true);
        $manager->persist($show);
        
        $manager->flush();
    }
}
