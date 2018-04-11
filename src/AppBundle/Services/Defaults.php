<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Services\Defaults.php

namespace AppBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Braincrafted\Bundle\BootstrapBundle\Session\FlashMessage;

/**
 * Defaults
 *
 */
class Defaults
{
    private $em;
    private $flash;

    public function __construct(EntityManagerInterface $em, FlashMessage $flash)
    {
        $this->em = $em;
        $this->flash = $flash;
    }

    public function showDefault()
    {
        return $this->em->getRepository('AppBundle:Show')->findOneBy(['default' => true]);
    }

    public function isActiveShowSet()
    {
        if (null === $this->showDefault()) {
            $this->flash->info('Set a show to Active first');

            return false;
        }

        return true;
    }

    public function hasArtistsInActiveShow()
    {
        $show = $this->showDefault();
        if (0 === count($show->getArtists())) {
            $this->flash->info('No artists in active show');

            return false;
        }

        return true;
    }

    public function hasReceiptsInActiveShow()
    {
        $show = $this->showDefault();
        if (0 === count($show->getReceipts())) {
            $this->flash->info('No receipts in active show');

            return false;
        }

        return true;
    }

    public function artistHasBlocksInShow($artist)
    {
        $blocks = $this->em->getRepository('AppBundle:Block')->findBy(['show' => $this->showDefault(), 'artist' => $artist]);
        if (0 === count($blocks)) {
            $this->flash->info('No tickets for this artist in active show');

            return false;
        }

        return true;
    }

    public function showHasBlocks()
    {
        $blocks = $this->showDefault()->getBlocks();
        if (0 === count($blocks)) {
            $this->flash->info('No tickets/blocks in active show');

            return false;
        }

        return true;
    }
}
