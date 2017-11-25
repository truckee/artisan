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

use AppBundle\Entity\Block;
use Doctrine\ORM\EntityRepository;

/**
 * BlockRepository
 *
 */
class ShowRepository extends EntityRepository
{
    /**
     * Creates block of first and last ticket numbers
     *
     * @param type $show
     * @return Block
     */
    public function createBlock($show)
    {
        $start = $show->getStart();
        $size = $show->getSize();
        $last = $start + $size - 1;
        $range = array($start, $last);

        $block = new Block();
        $block->setBlock($range);

        $show->setStart($last + 1);
        $em = $this->getEntityManager();
        $em->persist($show);
        $em->flush();

        return $block;
    }
}
