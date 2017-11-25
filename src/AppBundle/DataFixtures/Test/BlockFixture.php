<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\DataFixtures\Test\BlockFixture.php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Show;
use AppBundle\Entity\Block;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * BlockFixture
 *
 */
class BlockFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $start = 1;
        $size = $last = 10;
        
        $show = new Show();
        $show->setShow('Artisan Show 2017');
        $show->setStart($start);
        $show->setSize($size);
        $show->setDefault(true);
        $this->setReference('show', $show);


        $show2 = new Show();
        $show2->setShow('Artisan Show 2018');
        $show2->setStart($start);
        $show2->setSize($size);
        $show2->setDefault(true);


        $block1 = $manager->getRepository(Show::class)->createBlock($show);
        $block2 = $manager->getRepository(Show::class)->createBlock($show);
        
        $artist = new Artist();
        $artist->setFirstName('Benny');
        $artist->setLastName('Borko');
        $artist->addBlock($block1);
        $show->addBlock($block1);
        $artist->addBlock($block2);
        $show->addBlock($block2);
        $this->setReference('artist', $artist);
        $manager->persist($show);
        $manager->persist($show2);
        $manager->persist($artist);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
