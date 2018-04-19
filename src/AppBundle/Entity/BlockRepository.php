<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\BlockRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * BlockRepository
 *
 */
class BlockRepository extends EntityRepository
{
    private function qb($show)
    {
        return $this->getEntityManager()->createQueryBuilder()
                ->select('b')
                ->from('AppBundle:Block', 'b')
                ->join('AppBundle:Artist', 'a', 'WITH', 'b.artist = a')
                ->where('b.show = ?1');
    }

    public function getBlocksByArtists($show)
    {
        return $this->qb($show)
                ->orderBy('a.firstName')
                ->orderBy('a.lastName')
                ->setParameter(1, $show->getId())
                ->getQuery()
                ->getResult();
    }

    public function getBlocksByBlock($show)
    {
        return $this->qb($show)
                ->andWhere('b.upper <> 0')
                ->orderBy('b.lower')
                ->setParameter(1, $show->getId())
                ->getQuery()
                ->getResult();
    }

//    public function canBlockBeDeleted($show, $block)
//    {
//        $query = $this->getEntityManager()->createQueryBuilder()
//            ->select('COUNT(t.ticket)')
//            ->from('AppBundle:Ticket', 't')
//            ->join('AppBundle:Receipt', 'r', 'WITH', 't.receipt = r')
//            ->where('r.show = :show')
//            ->andWhere('t.ticket >= :lower')
//            ->andWhere('t.ticket <= :upper')
//            ->setParameters([
//                'show' => $show->getId(),
//                'lower' => $block->getLower(),
//                'upper' => $block->getUpper()
//                ])
//            ->getQuery()->getSingleScalarResult();
//
//        return ($query) ? false : true;
//    }
}
