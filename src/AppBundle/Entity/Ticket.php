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
use AppBundle\Validator\Constraints as AppAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
     * @Assert\Type(type="numeric", message = "Must be a number", groups={"add", "edit"})
     * @Assert\GreaterThan(value = 0, message = "Must be > 0", groups={"add", "edit"})
     * @AppAssert\TicketUsed(groups={"add"})
     * @AppAssert\TicketExists(groups={"add", "edit"})
     */
    private $ticket;

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
     * Set ticket
     *
     * @param string ticket
     *
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;

        return $this;
    }

    /**
     * Get ticket
     *
     * @return string
     */
    public function getTicket()
    {
        return $this->ticket;
    }
    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @Assert\NotBlank(message = "May not be empty", groups={"add", "edit"})
     * @Assert\NotEqualTo(value = 0, message = "May not = 0", groups={"add", "edit"})
     * @Assert\Type(type="numeric", message = "Must be a number", groups={"add", "edit"})
     */
    private $amount;

    /**
     * @Assert\Callback(groups={"edit"})
     */
    public function validateEdit(ExecutionContextInterface $context)
    {
        $receipt = $this->getReceipt();
        $total = 0;
        $tickets = $receipt->getTickets();
        foreach ($tickets as $value) {
            $total += ($this->getTicket() !== $value->getTicket()) ? $value->getAmount() : $this->getAmount() - $value->getAmount();
        }
        if (0 > $total) {
            $context->buildViolation('Artist\'s receipt total may not be < 0!')
                ->atPath('amount')
                ->addViolation();
        }
    }

    /**
     * @Assert\Callback(groups={"add"})
     */
    public function validateAdd(ExecutionContextInterface $context)
    {
        $total = $this->getRcptTotal();
        if (0 > $total + $this->getAmount()) {
            $context->buildViolation('Artist\'s receipt total may not be < 0!')
                ->atPath('amount')
                ->addViolation();
        }
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    private $rcptTotal;

    public function setRcptTotal($rcptTotal)
    {

       $this->rcptTotal = $rcptTotal;
    }

    public function getRcptTotal()
    {
        return $this->rcptTotal;
    }
}
