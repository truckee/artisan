<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\Percentage.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Percentage
 * @Annotation
 *
 */
class Percentage extends Constraint
{
    public $message = 'Must be between 0% and 100%';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}
