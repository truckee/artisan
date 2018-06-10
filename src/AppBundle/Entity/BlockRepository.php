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
        return $this->createQueryBuilder('b')
                ->join('b.artist', 'a')
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
}
