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
            ->join('AppBundle:Ticket', 't', 'WITH', 't.receipt = r')
            ->join('AppBundle:Artist', 'a', 'WITH', 't.artist = a')
            ->where('r.show = :show')
            ->setParameter(':show', $show->getId())
            ->groupBy('a.firstName')
            ->groupBy('a.lastName')
            ->getQuery()
            ->getResult();

        return $qb;
    }

    public function getSingleArtist($show, $artist)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('r')
            ->select('t.ticket, t.amount, r.id as receiptNo')
            ->from('AppBundle:Receipt', 'r')
            ->join('AppBundle:Ticket', 't', 'WITH', 't.receipt = r')
            ->join('AppBundle:Artist', 'a', 'WITH', 't.artist = a')
            ->where('r.show = :show')
            ->andWhere('a.id = :artist')
            ->setParameter(':show', $show->getId())
            ->setParameter(':artist', $artist->getId())
            ->getQuery()
            ->getResult();

        return $qb;
    }
}
