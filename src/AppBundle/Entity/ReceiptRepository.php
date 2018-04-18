<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\ReceiptRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ReceiptRepository
 *
 */
class ReceiptRepository extends EntityRepository
{

    public function nonzeroReceipts($show)
    {
        return $this->getEntityManager()->createQueryBuilder()
                ->select('r')
                ->from('AppBundle:Receipt', 'r')
                ->leftJoin('AppBundle:Nontaxable', 'n', 'WITH', 'r.nontaxable = n')
                ->leftJoin('AppBundle:Ticket', 't', 'WITH', 't.receipt = r')
                ->where('r.show = :show')
                ->setParameter('show', $show)
                ->groupBy('r')
                ->having('SUM((CASE WHEN t.amount IS NULL THEN 0 ELSE t.amount END) + (CASE WHEN n.amount IS NULL THEN 0 ELSE n.amount END)) > 0')
                ->getQuery()
                ->getResult();
    }

    public function receiptArtistTotal($receipt, $artist)
    {
        return $this->getEntityManager()->createQueryBuilder()
            ->select('SUM(t.amount)')
            ->from('AppBundle:Receipt', 'r')
            ->join('AppBundle:Ticket', 't', 'WITH', 't.receipt = r')
            ->join('AppBundle:Artist', 'a', 'WITH', 't.artist = a')
            ->where('r = :receipt')
            ->andWhere('a = :artist')
            ->setParameters(['receipt' => $receipt, 'artist' => $artist])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function receiptsInShow($show)
    {
        return $this->createQueryBuilder('r')
            ->where('r.show = :show')
            ->setParameter('show', $show)
            ->getQuery()->getResult();
    }
}
