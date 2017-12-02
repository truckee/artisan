<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\ShowTicketValidator.php

namespace AppBundle\Validator\Constraints;

use AppBundle\Services\Defaults;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * ShowTicketValidator
 *
 */
class ShowTicketValidator extends ConstraintValidator
{
    private $em;
    private $defaults;

    public function __construct(EntityManagerInterface $em, Defaults $defaults)
    {
        $this->em = $em;
        $defaults = $this->defaults;
    }

    public function validate($ticket, Constraint $constraint)
    {
        $show = $this->defaults->showDefault();
        $blocks = $this->em->getRepository('AppBundle:Block')->findBy(['show' => $show]);
//        $found = false;
        foreach ($blocks as $value) {
            $block = $value->getBlock();
            if ($block[0] <= $ticket && $ticket <= $block[1]) {
//                $found = true;

                return;
            }
        }
        $this->context->buildViolation($constraint->message)
            ->atPath('ticket')
            ->addViolation();
    }
}
