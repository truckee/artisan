<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Services\PdfService.php

namespace AppBundle\Services;

/**
 * PdfService
 *
 */
class PdfService
{
    private $os;

    public function __construct($os)
    {
        $this->os = $os;
    }

    public function pdfExecutable()
    {
        return $this->os;
    }
}
