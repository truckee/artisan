<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\PercentageValidator.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * PercentageValidator
 *
 */
class PercentageValidator extends ConstraintValidator
{

    public function validate($percent, Constraint $constraint)
    {
        if ($percent < 0 || $percent > 1) {
            $this->context->buildViolation($constraint->message)
//                ->atPath('tax')
                ->addViolation();
        }
    }
}
