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

use AppBundle\Entity\Block;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="show")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ShowRepository")
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
        $this->receipt = new ArrayCollection();
    }

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message="Show may not be empty")
     */
    private $show;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value = 0, message="Starting number > 0")
     */
    private $start;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value = 0, message="Block size > 0")
     */
    private $size;

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
     * Set start
     *
     * @param string $start
     *
     * @return Start
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return Size
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
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
}
