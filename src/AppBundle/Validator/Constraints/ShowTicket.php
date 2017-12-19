<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\ShowTicket.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * ShowTicket
 * @Annotation
 */
class ShowTicket extends Constraint
{
    public $message = 'Artist reeipt not found';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
