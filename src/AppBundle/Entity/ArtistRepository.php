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

    public function allArtistsInShow($show)
    {
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
    }

    public function isArtistInShow($show, $artist)
    {
        $qb = $this->getEntityManager()->createQueryBuilder()
            ->select('a')
            ->from('AppBundle:Artist', 'a')
            ->join('a.shows', 's')
            ->where('s.show = ?1')
            ->andWhere('a = ?2')
            ->setParameter(1, $show->getShow())
            ->setParameter(2, $artist)
            ->getQuery()
            ->getResult();

        return !empty($qb);
    }

    public function someNotInShow($show)
    {
        $qb = $this->getEntityManager()->createQueryBuilder('a');
        $ids = $qb
            ->select('a')
            ->from('AppBundle:Artist', 'a')
            ->leftJoin('a.shows', 's')
            ->where('s.show = ?1')
            ->setParameter(1, $show->getShow())
            ->getQuery()
            ->getResult();
        if (empty($ids)) {
            return $this->getEntityManager()->createQueryBuilder('a')
                    ->select('a')
                    ->from('AppBundle:Artist', 'a');
        } else {
            $maybe = $this->getEntityManager()->createQueryBuilder('a')
                ->select('a')
                ->from('AppBundle:Artist', 'a')
                ->where($this->getEntityManager()->createQueryBuilder('a')->expr()->notIn('a.id', ':ids'))
                ->setParameter(':ids', $ids);

            return $maybe;
        }
    }
}
