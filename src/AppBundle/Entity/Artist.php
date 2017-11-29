<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\Artist.php

namespace AppBundle\Entity;

use AppBundle\Entity\Block;
use AppBundle\Entity\Show;
use AppBundle\Entity\Ticket;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="artist")
 */
class Artist
{
    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message="First name may not be empty")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message="Last name may not be empty")
     */
    private $lastName;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function __construct()
    {
        $this->blocks = new ArrayCollection();
        $this->shows = new ArrayCollection();
        $this->ticketnumbers = new ArrayCollection();
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Block", mappedBy="artist", cascade={"persist"}, orphanRemoval=true)
     */
    protected $blocks;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Show", mappedBy="artists")
     */
    protected $shows;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="artist", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"ticketnumber" = "ASC"})
     */
    protected $ticketnumbers;

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Artist
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Artist
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

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

    public function addBlock(Block $block)
    {
        $this->blocks[] = $block;
        $block->setArtist($this);

        return $this;
    }

    public function removeBlock(Block $block)
    {
        $this->blocks->removeElement($block);
    }

    public function addShow(Show $show)
    {
        $this->shows[] = $show;
        $show->setArtist($this);

        return $this;
    }

    public function removeShow(Show $show)
    {
        $this->shows->removeElement($show);
    }

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private $address;

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private $city;

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private $state;

    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private $zip;

    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private $email;

    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private $phone;

    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private $dba;

    public function setDba($dba)
    {
        $this->dba = $dba;

        return $this;
    }

    public function getDba()
    {
        return $this->dba;
    }

    /**
     * @ORM\Column(type="string", nullable = true)
     */
    private $tax_id;

    public function setTax_id($tax_id)
    {
        $this->tax_id = $tax_id;

        return $this;
    }

    public function getTax_id()
    {
        return $this->tax_id;
    }

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    private $vendor;

    public function setVendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    private $confirmed;

    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @ORM\Column(type="boolean", nullable = true)
     */
    private $tax_form;

    public function setTax_form($tax_form)
    {
        $this->tax_form = $tax_form;

        return $this;
    }

    public function getTax_form()
    {
        return $this->tax_form;
    }
}
