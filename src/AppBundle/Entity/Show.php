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

use AppBundle\Entity\Artist;
use AppBundle\Entity\Block;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="art_show")
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
        $this->artists = new ArrayCollection();
        $this->blocks = new ArrayCollection();
        $this->receipt = new ArrayCollection();
    }

    /**
     * @ORM\Column(name="art_show", type="string", length=45)
     * @Assert\NotBlank(message="Show may not be empty")
     */
    private $show;

    /**
     * @ORM\Column(name="is_default", type="boolean", options={"default"=false})
     */
    private $default;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $tax;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $percent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Artist", mappedBy="shows")
     *
     */
    protected $artists;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

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
     * Set default
     *
     * @param string $default
     *
     * @return Default
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default
     *
     * @return string
     */
    public function isDefault()
    {
        return $this->default;
    }

    /**
     * Set tax
     *
     * @param string $tax
     *
     * @return Tax
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return string
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set percent
     *
     * @param string $percent
     *
     * @return Percent
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return string
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Block", mappedBy="show", cascade={"persist"}, orphanRemoval=true)
     */
    protected $blocks;

    public function addBlock(Block $block)
    {
        $this->blocks[] = $block;
        $block->setShow($this);

        return $this;
    }

    public function removeBlock(Block $block)
    {
        $this->blocks->removeElement($block);
    }

    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Receipt", mappedBy="show", cascade={"persist"}, orphanRemoval=true)
     */
    protected $receipts;

    public function addReceipt(Receipt $receipt)
    {
        $this->receipts[] = $receipt;
        $receipt->setShow($this);

        return $this;
    }

    public function removeReceipt(Receipt $receipt)
    {
        $this->receipts->removeElement($receipt);
    }

    public function getReceipts()
    {
        return $this->receipts;
    }

    public function addArtist(Artist $artist)
    {
        $artist->addShow($this); // synchronously updating inverse side
        $this->artists[] = $artist;
    }

    public function removeArtist(Artist $artist)
    {
        $this->artists->removeElement($artist);
    }

    public function getArtists()
    {
        return $this->artists;
    }
}
