<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\ShowRepository.php

namespace AppBundle\Entity;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Ticket;

use Doctrine\ORM\EntityRepository;

/**
 * ReceiptRepository
 *
 */
class ShowRepository extends EntityRepository
{
    public function getShowSummary($show)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('r')
            ->select('a.lastName, a.firstName, SUM(t.amount) as total')
            ->from('AppBundle:Receipt', 'r')
            ->join('r.tickets', 't')
            ->join('t.artist', 'a')
            ->where('r.show = :show')
            ->setParameter(':show', $show)
            ->groupBy('a.lastName, a.firstName')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function getSingleArtist($show, $artist)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('r')
            ->select('t.ticket, t.amount, r.id as receiptNo')
            ->from('AppBundle:Receipt', 'r')
            ->join('r.tickets', 't')
            ->join('t.artist', 'a')
            ->where('r.show = :show')
            ->andWhere('a = :artist')
            ->orderBy('t.ticket', 'ASC')
            ->setParameter(':show', $show)
            ->setParameter(':artist', $artist)
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function getShowNontaxable($show)
    {
        return $this->getEntityManager()->createQueryBuilder('r')
            ->select('SUM(n.amount)')
            ->from('AppBundle:Receipt', 'r')
            ->leftJoin('r.nontaxable', 'n')
            ->where('r.show = :show')
            ->setParameter(':show', $show)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
