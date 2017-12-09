<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\Block.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Artist;
use AppBundle\Entity\Show;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_block")
 * @AppAssert\BlockLimits
 * @AppAssert\UniqueBlock
 */
class Block
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value = 0, message = "Must be > 0")
     */
    private $lower;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value=0, message="Must be > 0")
     */
    private $upper;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Artist", inversedBy="blocks", fetch="EAGER")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     */
    protected $artist;

    public function getId()
    {
        return $this->id;
    }

    public function setArtist(Artist $artist)
    {
        $this->artist = $artist;

        return $this;
    }

    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Show", inversedBy="blocks")
     * @ORM\JoinColumn(name="show_id", referencedColumnName="id")
     */
    protected $show;

    public function setShow(Show $show)
    {
        $this->show = $show;

        return $this;
    }

    public function getShow()
    {
        return $this->show;
    }

    public function setLower($lower)
    {
        $this->lower = $lower;

        return $this;
    }

    public function getLower()
    {
        return $this->lower;
    }

    public function setUpper($upper)
    {
        $this->upper = $upper;

        return $this;
    }

    public function getUpper()
    {
        return $this->upper;
    }
}
