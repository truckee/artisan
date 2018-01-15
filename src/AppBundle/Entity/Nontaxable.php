<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\Nontaxable.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Nontaxable
 * @ORM\Entity
 * @ORM\Table(name="nontaxable")
 */
class Nontaxable
{
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
     * @ORM\Column(type="decimal", precision=8, scale=2, nullable = true)
     * @Assert\NotBlank(message = "May not be empty")
     * @Assert\Type(type="numeric", message = "Must be a number")
     * @Assert\GreaterThan(value=0, message = "Must be > 0")
     */
    protected $amount;

    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }
    
}
