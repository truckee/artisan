<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\UniqueBlockValidator.php

namespace AppBundle\Validator\Constraints;

use AppBundle\Services\Defaults;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * UniqueBlockValidator
 *
 */
class UniqueBlockValidator extends ConstraintValidator
{
    private $em;
    private $defaults;

    public function __construct(EntityManagerInterface $em, Defaults $defaults)
    {
        $this->em = $em;
        $this->defaults = $defaults;
    }

    public function validate($block, Constraint $constraint)
    {
        $show = $this->defaults->showDefault();
        $artist = $block->getArtist();
        $samePerson = $this->em->getRepository('AppBundle:Block')->findBy(['show' => $show, 'artist' => $artist]);
        $passes = false;
        //if block has shrunk in edit no need to check further
        foreach ($samePerson as $possible) {
            if ($block->getLower() >= $possible->getLower() && $block->getUpper() <= $possible->getUpper()) {
                $passes = true;
            }
        }
        if (true === $passes) {
            return;
        }
        //otherwise, check all blocks for show
        $existing = $this->em->getRepository('AppBundle:Block')->findBy(['show' => $show]);
        foreach ($existing as $possible) {
            if ($block->getLower() <= $possible->getUpper() && $block->getUpper() >= $possible->getLower()) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('block')
                    ->addViolation();
            }
        }
    }
}
