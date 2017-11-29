<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\UniqueArtistNameValidator.php

namespace AppBundle\Validator\Constraints;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * ArtistNameValidator
 *
 */
class UniqueArtistNameValidator extends ConstraintValidator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function validate($artist, Constraint $constraint)
    {
        $nameArray = ['firstName' => $artist->getFirstName(), 'lastName' => $artist->getLastName()];
        $nameExists = $this->em->getRepository('AppBundle:Artist')->findOneBy($nameArray);
        if (null !== $nameExists) {
            $this->context->buildViolation($constraint->message)
                ->atPath('artist')
                ->addViolation();
        }
    }
}
