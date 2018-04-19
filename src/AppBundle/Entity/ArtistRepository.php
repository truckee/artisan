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
        return $this->createQueryBuilder('a')
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
        $qb = $this->createQueryBuilder('a')
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
        $qb = $this->createQueryBuilder('a');
        $ids = $qb
            ->leftJoin('a.shows', 's')
            ->where('s.show = ?1')
            ->setParameter(1, $show->getShow())
            ->getQuery()
            ->getResult();
        if (empty($ids)) {
            return $this->createQueryBuilder('a');
        } else {
            $maybe = $this->getEntityManager()->createQueryBuilder('a')
                ->select('a')
                ->from('AppBundle:Artist', 'a')
                ->where($this->getEntityManager()->createQueryBuilder('a')->expr()->notIn('a.id', ':ids'))
                ->setParameter(':ids', $ids);

            return $maybe;
        }
    }

    public function artistShowTickets($show)
    {
        $showArtists = [];
        $artists = $this->allArtistsInShow($show);
        foreach ($artists as $artist) {
            $id = $artist->getId();
            $showArtists[$id]['artist'] = $artist;
            $tickets = $this->getEntityManager()->getRepository('AppBundle:Show')->getSingleArtist($show, $artist);
            $showArtists[$id]['tickets'] = $tickets;
        }

        return $showArtists;
    }

    public function getBlockOrTickets($show)
    {
        return $this->createQueryBuilder('a')
                ->join('a.shows', 's')
                ->where('s.show = ?1')
                ->orderBy('a.firstName')
                ->orderBy('a.lastName')
                ->setParameter(1, $show->getShow());
    }

    public function getAllSelectable()
    {
        return $this->createQueryBuilder('a')
                ->orderBy('a.firstName')
                ->orderBy('a.lastName');
    }

    public function getReplacementArtists($notId)
    {
        return $this->createQueryBuilder('a')
                ->where('a <> :notId')
                ->setParameter('notId', $notId)
                ->orderBy('a.firstName', 'ASC')
                ->orderBy('a.lastName', 'ASC');
    }

    public function deleteableArtists()
    {
        return $this->createQueryBuilder('a')
                ->leftJoin('AppBundle:Ticket', 't', 'WITH', 't.artist = a')
                ->groupBy('a.firstName')
                ->groupBy('a.lastName')
                ->having('SUM(t.amount) = 0')
                ->orHaving('SUM(t.amount) IS NULL')
                ->orderBy('a.firstName', 'ASC')
                ->orderBy('a.lastName', 'ASC')
            ;
        ;
    }

    public function canBeReplaced($show)
    {
        return $this->createQueryBuilder('a')
                ->join('a.shows', 's')
                ->join('a.blocks', 'b')
                ->where('s.show = ?1')
                ->groupBy('a.firstName')
                ->groupBy('a.lastName')
                ->having('count(b) > 0')
                ->setParameter(1, $show->getShow());
    }

    public function processQuery($query)
    {
        return $query->getQuery()->getResult();
    }

    public function showArtistNonZeroTicketSum($show)
    {
        return $this->createQueryBuilder('a')
                ->join('AppBundle:Ticket', 't', 'WITH', 't.artist = a')
                ->join('AppBundle:Receipt', 'r', 'WITH', 't.receipt = r')
                ->where('t.artist = a')
                ->andWhere('r.show = :show')
                ->having('SUM(t.amount) > 0')
                ->groupBy('a.firstName')
                ->groupBy('a.lastName')
                ->setParameter('show', $show)
                ->getQuery()->getResult();
        ;
    }
}
