<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\TicketRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TicketRepository
 *
 */
class TicketRepository extends EntityRepository
{
    public function searchTickets($number)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('t.ticket, s.show, r.id receipt, a.lastName, a.firstName')
            ->from('AppBundle:Ticket', 't')
            ->join('t.receipt', 'r')
            ->join('r.show', 's')
            ->join('t.artist', 'a')
            ->where('t.ticket = :number')
            ->setParameter('number', $number)
            ->orderBy('s.show', 'ASC')
            ->getQuery()->getResult();
    }
}
