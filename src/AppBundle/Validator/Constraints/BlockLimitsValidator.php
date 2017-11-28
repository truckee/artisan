<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Validator\Constraints\BlockLimitsValidator.php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * BlockLimitsValidator
 *
 */
class BlockLimitsValidator extends ConstraintValidator
{
    public function validate($block, Constraint $constraint)
    {
        if ($block->getLower() > $block->getUpper()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('block')
                ->addViolation();
        }
    }}
