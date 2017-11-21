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

    public function addTicketnumber(Ticket $ticketnumber)
    {
        $this->ticketnumbers[] = $ticketnumber;
        $ticketnumber->setArtist($this);

        return $this;
    }

    public function removeTicketnumber(Ticket $ticketnumber)
    {
        $this->ticketnumbers->removeElement($ticketnumber);
    }
    
}
