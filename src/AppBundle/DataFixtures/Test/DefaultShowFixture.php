<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\DataFixtures\Test\DefaultShowFixture.php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\Show;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * DefaultShowFixtureFixture
 *
 */
class DefaultShowFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $show = new Show();
        $show->setShow('Artisan Show 2017');
        $show->setDefault(true);
        $manager->persist($show);
        $this->setReference('show', $show);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
