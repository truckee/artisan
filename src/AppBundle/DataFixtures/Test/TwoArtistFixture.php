<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\DataFixtures\Test\TwoArtistFixture.php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\Artist;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * ArtistFixture
 *
 */
class TwoArtistFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $artist = new Artist();
        $artist->setFirstName('Benny');
        $artist->setLastName('Borko');
        $this->setReference('artist', $artist);
        $manager->persist($artist);

        $artist2 = new Artist();
        $artist2->setFirstName('Al');
        $artist2->setLastName('Einstein');
        $this->setReference('artist2', $artist2);
        $manager->persist($artist2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
