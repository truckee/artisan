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

/**
 * Defaults
 *
 */
class Defaults
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function showDefault()
    {
        $default = $this->em->getRepository('AppBundle:Show')->findBy(['default' => true]);

        return $default;
    }
}
