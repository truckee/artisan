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
        $qb = $this->getEntityManager()->createQueryBuilder()
                ->select('r, SUM((CASE WHEN t.amount IS NULL THEN 0 ELSE t.amount END) + (CASE WHEN n.amount IS NULL THEN 0 ELSE n.amount END)) Total')
                ->from('AppBundle:Receipt', 'r')
                ->leftJoin('AppBundle:Nontaxable', 'n', 'WITH', 'r.nontaxable = n')
                ->leftJoin('AppBundle:Ticket', 't', 'WITH', 't.receipt = r')
                ->where('r.show = :show')
                ->setParameter('show', $show)
                ->groupBy('r')
                ->having('Total > 0')
                ->getQuery()
                ->getResult();

        foreach ($qb as $rcpArray) {
            $receipts[] = $rcpArray[0];
        }

        return $receipts;
    }
}
