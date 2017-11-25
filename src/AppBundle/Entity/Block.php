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

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket_block")
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
     * @ORM\Column(type="array")
     */
    private $block;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Artist", inversedBy="blocks")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     */
    protected $artist;

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

    public function setBlock($block)
    {
        $this->block = $block;

        return $this;
    }

    public function getBlock()
    {
        return $this->block;
    }
}
