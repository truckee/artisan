<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\ArtistRepository.php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ArtistRepository
 *
 */
class ArtistRepository extends EntityRepository
{

    public function inShow($show)
    {
//        $qb = $this->getEntityManager()->createQueryBuilder();

        return $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->from('AppBundle:Artist', 'a')
            ->join('a.shows', 's')
            ->where('s.show = ?1')
            ->orderBy('a.firstName')
            ->orderBy('a.lastName')
            ->setParameter(1, $show->getShow())
            ->getQuery()
            ->getResult();

//        $ids = array_keys($ids);

//        $qbA = $this->getEntityManager()->createQuery(
//            'SELECT a FROM AppBundle:Artist a '
//            . 'WHERE a.id NOT IN (:ids)')
//            ->setParameter(':ids', $ids)
//            ->getResult();

//        return $ids;
    }
}
