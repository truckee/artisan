<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\TicketExists.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * TicketExists
 * @Annotation
 *
 */
class TicketExists extends Constraint
{
    public $message = 'Ticket does not exist';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
