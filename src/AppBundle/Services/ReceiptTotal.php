<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Services\ReceiptTotal.php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

/**
 * ReceiptTotal
 *
 */
class ReceiptTotal
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getReceiptTotal($receipt)
    {
        $tickets = $receipt->getTickets();
        $total = 0;
        if (null !== $tickets) {
            foreach ($tickets as $value) {
                $total += $value->getAmount();
            }
        }

        return $total;
    }
}
