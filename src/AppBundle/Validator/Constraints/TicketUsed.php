<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\TicketUsed.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * TicketUsed
 * @Annotation
 *
 */
class TicketUsed extends Constraint
{
    public $message = 'Ticket has already been used';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
