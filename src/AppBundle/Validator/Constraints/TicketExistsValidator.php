<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\TicketExistsValidator.php

namespace AppBundle\Validator\Constraints;

use AppBundle\Services\TicketArtist;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * TicketExistsValidator
 *
 */
class TicketExistsValidator extends ConstraintValidator
{
    private $artist;

    public function __construct(TicketArtist $artist)
    {
        $this->artist = $artist;
    }

    public function validate($ticket, Constraint $constraint)
    {
        $entity = $this->artist->getTicketArtist($ticket);
        if (null === $entity) {
            $this->context->buildViolation($constraint->message)
//                ->atPath('block')
                ->addViolation();
        }
    }
}
