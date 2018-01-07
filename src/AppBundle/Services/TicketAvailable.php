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
 * TicketUsed
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
        $ticket = (is_object($incoming)) ? $incoming->getTicket() : $incoming;
        //ticket already used?
        $receipts = $this->em->getRepository('AppBundle:Receipt')->findBy(['show' => $show]);
        foreach ($receipts as $receipt) {
            foreach ($receipt->getTickets() as $entity) {
                if ($entity->getTicket() == $ticket) {
                    return null;
                }
            }
        }

        return true;
    }
}
