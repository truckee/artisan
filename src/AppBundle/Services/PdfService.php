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
        switch ($this->os) {
            case 'windows':
                return "\"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe\" -T 25 -R 25 -B 25 -L 25";
//                return "\"C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe\" --zoom \"1.1\"";
            case 'ubuntu':
                return '/usr/local/bin/wkhtmltopdf';

            default:
                break;
        }
    }
}
