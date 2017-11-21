<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\Show.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="show")
 * @UniqueEntity("show", message = "Show already exists")
 */
class Show
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function __construct()
    {
        $this->artist = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message="Show may not be empty")
     */
    private $show;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value = 0, message="Ticket number > 0")
     */
    private $lowest;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value = 0, message="Ticket number > 0")
     */
    private $highest;

    /**
     * Set show
     *
     * @param string $show
     *
     * @return Show
     */
    public function setShow($show)
    {
        $this->show = $show;

        return $this;
    }

    /**
     * Get show
     *
     * @return string
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Set lowest
     *
     * @param string $lowest
     *
     * @return Lowest
     */
    public function setLowest($lowest)
    {
        $this->lowest = $lowest;

        return $this;
    }

    /**
     * Get lowest
     *
     * @return string
     */
    public function getLowest()
    {
        return $this->lowest;
    }

    /**
     * Set highest
     *
     * @param string $highest
     *
     * @return Highest
     */
    public function setHighest($highest)
    {
        $this->highest = $highest;

        return $this;
    }

    /**
     * Get highest
     *
     * @return string
     */
    public function getHighest()
    {
        return $this->highest;
    }
}
