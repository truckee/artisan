<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\DataFixtures\Test\ShowFixture.php

namespace AppBundle\DataFixtures\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * ShowFixture
 *
 */
class ShowFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $show = $this->getReference('show');
        $receipt = $this->getReference('receipt');
        $receipt->setShow($show);
        $show->setDefault(true);
        $manager->persist($receipt);

        $artist2 = $this->getReference('artist2');
        $artist2->addShow($show);
        $block2 = $this->getReference('block2');
        $block2->setArtist($artist2);
        $show->addBlock($block2);
        $manager->persist($block2);
        $manager->persist($show);

        $manager->flush();
    }

    public function getOrder()
    {
        return 6; // the order in which fixtures will be loaded
    }
    
}
