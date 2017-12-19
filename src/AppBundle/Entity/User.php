<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Entity\User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message="First name may not be empty", groups = {"add", "edit"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\NotBlank(message="Last name may not be empty", groups = {"add", "edit"})
     */
    private $lastName;

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }
    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function hasRoleAdmin()
    {
        return ($this->hasRole('ROLE_ADMIN')) ? 'Yes' : 'No';
    }

    public function setHasRoleAdmin($isAdmin)
    {
        if ('Yes' === $isAdmin && 'No' === $this->hasRole('ROLE_ADMIN')) {
            $this->addRole('ROLE_ADMIN');
        }
        if ('No' === $isAdmin && 'Yes' == $this->hasRole('ROLE_ADMIN')) {
            $this->removeRole('ROLE_ADMIN');
        }
        $this->isAdmin = $isAdmin;
    }
}
