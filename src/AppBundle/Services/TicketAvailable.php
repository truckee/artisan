<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Services\TicketAvailable.php

namespace AppBundle\Services;

use AppBundle\Services\Defaults;
use Doctrine\ORM\EntityManagerInterface;

/**
 * TicketAvailable
 *
 */
class TicketAvailable
{
    private $em;
    private $defaults;

    public function __construct(EntityManagerInterface $em, Defaults $defaults)
    {
        $this->em = $em;
        $this->defaults = $defaults;
    }

    public function isTicketAvailable($incoming)
    {
        $show = $this->defaults->showDefault();
        $ticket = $this->em->getRepository('AppBundle:Ticket')->findOneBy(['ticket' => $incoming]);
        //ticket already used?
        $receipts = $this->em->getRepository('AppBundle:Receipt')->findBy(['show' => $show]);
        foreach ($receipts as $receipt) {
//            if ($receipt->getTickets()->contains($ticket)) {
//                return null;
//            }
//        }
//          a brute force method that works
            foreach ($receipt->getTickets() as $entity) {
                if ($entity->getTicket() == $ticket->getTicket()) {
                    return null;
                }
            }
        }

        return true;
    }
}
