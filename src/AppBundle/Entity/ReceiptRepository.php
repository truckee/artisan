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

    public function getNewReceiptNo()
    {
        $largest= $this->getEntityManager()->createQueryBuilder('r')
                ->select('MAX(r.id)')
                ->from('AppBundle:Receipt', 'r')
                ->getQuery()
                ->getSingleScalarResult();

        return $largest + 1125;
    }
}
