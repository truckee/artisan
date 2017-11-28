<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\BlockLimits.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * BlockLimits
 * @Annotation
 *
 */
class BlockLimits extends Constraint
{
    public $message = 'Block upper end must be greater than or equal to lower end';

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
