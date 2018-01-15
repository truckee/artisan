<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\Receipt.php

namespace AppBundle\Entity;

use AppBundle\Entity\Show;
use AppBundle\Entity\Ticket;
use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="receipt")
 * @AppAssert\NonzeroReceipt
 */
class Receipt
{

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="receipt", cascade={"persist"}, orphanRemoval=true, fetch="EAGER")
     * @ORM\OrderBy({"ticket" = "ASC"})
     * @Assert\Valid()
     */
    protected $tickets;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Show", inversedBy="receipts")
     * @ORM\JoinColumn(name="show_id", referencedColumnName="id")
     */
    protected $show;

    /**
     * @ORM\OneToOne(targetEntity="Nontaxable")
     */
    protected $nontaxable;

    public function addTicket(Ticket $ticket)
    {
        $this->tickets[] = $ticket;
        $ticket->setReceipt($this);

        return $this;
    }

    public function removeTicket(Ticket $ticket)
    {
        $this->tickets->removeElement($ticket);
    }

    public function getTickets()
    {
        return $this->tickets;
    }

    public function setShow(Show $show)
    {
        $this->show = $show;

        return $this;
    }

    public function getShow()
    {
        return $this->show;
    }

    /**
     * @ORM\Column(name="sales_date", type="date", nullable = false)
     */
    private $salesDate;

    public function setSalesDate($salesDate)
    {
        $this->salesDate = $salesDate;

        return $this;
    }

    public function getSalesDate()
    {
        return $this->salesDate;
    }

    public function setNontaxable($nontaxable)
    {
        $this->nontaxable = $nontaxable;

        return $this;
}

    public function getNontaxable()
    {
        return $this->nontaxable;
    }
}
