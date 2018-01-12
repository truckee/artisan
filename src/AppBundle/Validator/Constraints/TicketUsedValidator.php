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

use AppBundle\Services\TicketAvailable;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * TicketUsedValidator
 *
 */
class TicketUsedValidator extends ConstraintValidator
{
    private $check;

    public function __construct(TicketAvailable $check)
    {
        $this->check = $check;
    }

    public function validate($ticket, Constraint $constraint)
    {
        $entity = $this->check->isTicketAvailable($ticket);
        if (null === $entity) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
