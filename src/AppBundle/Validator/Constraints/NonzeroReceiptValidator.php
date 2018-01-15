<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\NonzeroReceiptValidator.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * ReceiptValidator
 *
 */
class NonzeroReceiptValidator extends ConstraintValidator
{
    public function validate($receipt, Constraint $constraint)
    {
        if (null === $receipt->getNontaxable()) {
                $this->context->buildViolation($constraint->message)
                    ->atPath('block')
                    ->addViolation();
        }

    }
}
