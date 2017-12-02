<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\Ticket.php

namespace AppBundle\Entity;

use AppBundle\Entity\Artist;
use AppBundle\Entity\Receipt;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="ticket")
 */
class Ticket
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Artist", inversedBy="tickets")
     * @ORM\JoinColumn(name="artist_id", referencedColumnName="id")
     */
    protected $artist;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Receipt", inversedBy="tickets")
     * @ORM\JoinColumn(name="receipt_id", referencedColumnName="id")
     */
    protected $receipt;
    
    /**
     * @ORM\Column(type="integer")
     */
    private $ticketnumber;

    /**
     * Get id
     *
     * @return integer
     */
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

    public function setReceipt(Receipt $receipt)
    {
        $this->receipt = $receipt;

        return $this;
    }

    public function getReceipt()
    {
        return $this->receipt;
    }

    /**
     * Set ticketnumber
     *
     * @param string ticketnumber
     *
     */
    public function setTicketnumber($ticketnumber)
    {
        $this->ticketnumber = $ticketnumber;

        return $this;
    }

    /**
     * Get ticketnumber
     *
     * @return string
     */
    public function getTicketnumber()
    {
        return $this->ticketnumber;
    }

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     */
    private $amount;

    public function setAmount(Amount $amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }
}
