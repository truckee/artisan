<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\DataFixtures\Test\ReceiptFixture.php

namespace AppBundle\DataFixtures\Test;

use AppBundle\Entity\Receipt;
use AppBundle\Entity\Ticket;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * ReceiptFixture
 *
 */
class ReceiptFixture extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $ticket = new Ticket();
        $ticket->setAmount(56.79);
        $artist = $this->getReference('artist');
        $ticket->setArtist($artist);
        $ticket->setTicket(1);
        $ticket2 = new Ticket();
        $ticket2->setAmount(12.34);
        $artist = $this->getReference('artist');
        $ticket2->setArtist($artist);
        $ticket2->setTicket(2);


        $receipt = new Receipt();
        $receipt->addTicket($ticket);
        $receipt->addTicket($ticket2);
        $receipt->setSalesDate(new \DateTime());
        $receipt->setReceiptNo(1001);
        $manager->persist($ticket);
        $manager->persist($receipt);

        $manager->flush();

    }

    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}
