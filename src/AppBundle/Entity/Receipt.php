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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="receipt")
 */
class Receipt
{

    public function __construct()
    {
        $this->ticketnumbers = new ArrayCollection();
    }
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="receipt", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"ticketnumber" = "ASC"})
     */
    protected $ticketnumbers;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Show", inversedBy="receipts")
     * @ORM\JoinColumn(name="show_id", referencedColumnName="id")
     */
    protected $show;

    public function addTicketnumber(Ticket $ticketnumber)
    {
        $this->ticketnumbers[] = $ticketnumber;
        $ticketnumber->setShow($this);

        return $this;
    }

    public function removeTicketnumber(Ticket $ticketnumber)
    {
        $this->ticketnumbers->removeElement($ticketnumber);
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
     * @ORM\Column(type="date", nullable = false)
     */
    private $sales_date;

    public function setSales_date(Sales_date $sales_date)
    {
        $this->sales_date = $sales_date;

        return $this;
    }

    public function getSales_date()
    {
        return $this->sales_date;
    }
}
