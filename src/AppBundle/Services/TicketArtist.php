<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Services\TicketArtist.php

namespace AppBundle\Services;

use AppBundle\Services\Defaults;
use Doctrine\ORM\EntityManagerInterface;

/**
 * TicketArtist
 *
 */
class TicketArtist
{
    private $em;
    private $defaults;

    public function __construct(EntityManagerInterface $em, Defaults $defaults)
    {
        $this->em = $em;
        $this->defaults = $defaults;
    }

    public function getTicketArtist($ticket)
    {
        $show = $this->defaults->showDefault();
        $blocks = $this->em->getRepository('AppBundle\Entity\Block')->findBy(['show' => $show]);
        if (empty($blocks)) {
            return null;
        }
        foreach ($blocks as $block) {
            if ($block->getLower() <= $ticket && $ticket <= $block->getUpper()) {
                $artist = $block->getArtist();

                return $artist;
            } else {
                return null;
            }
        }
    }

}
