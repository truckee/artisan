<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\DataFixtures\Test\BlocksToShowFixture.php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\Block;
use AppBundle\Entity\Show;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * BlocksToShowFixture
 *
 */
class BlocksToShowFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $block = new Block();
        $block->setLower(1);
        $block->setUpper(10);
        $show = $this->getReference('show');
        $artist = $this->getReference('artist');
        $block->setShow($show);
        $block->setArtist($artist);
        $this->setReference('block', $block);
        $manager->persist($block);

        //to test block not in show
        $block2 = new Block();
        $block2->setLower(20);
        $block2->setUpper(29);
        $show2 = new Show();
        $show2->setShow('Art 2018');
        $show2->setDefault(false);
        $show2->addBlock($block2);
        $manager->persist($block2);
        $manager->persist($show2);

        $manager->flush();
    }
    

    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
