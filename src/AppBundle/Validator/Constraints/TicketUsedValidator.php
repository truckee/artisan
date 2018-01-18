<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\TicketUsedValidator.php

namespace AppBundle\Validator\Constraints;

//use AppBundle\Services\TicketAvailable;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Services\Defaults;

/**
 * TicketUsedValidator
 *
 */
class TicketUsedValidator extends ConstraintValidator
{
    private $em;
    private $defaults;

    public function __construct(EntityManagerInterface $em, Defaults $defaults)
    {
        $this->em = $em;
        $this->defaults = $defaults;
    }

    public function validate($ticket, Constraint $constraint)
    {
        $show = $this->defaults->showDefault();
        $entities = $this->em->getRepository('AppBundle:Ticket')->findBy(['ticket' => $ticket]);
        if (null !== $entities) {
            foreach ($entities as $possible) {
                if ($show === $possible->getReceipt()->getShow()) {
                    $this->context->buildViolation($constraint->message)->addViolation();
                    return;
                }
            }
        }
    }
}
