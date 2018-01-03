<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\PDFController.php

namespace AppBundle\Controller;

use AppBundle\Services\PdfService;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * PDFController
 * @Route("/pdf")
 *
 */
class PDFController extends Controller
{

    /**
     * @Route("/showSummary/{id}", name="show_summary_pdf")
     */
    public function showSummaryPDFAction(PdfService $pdf, $id = null)
    {
        if (null !== $id) {
            $em = $this->getDoctrine()->getManager();
            $show = $em->getRepository('AppBundle:Show')->find($id);
        } else {
            return $this->redirectToRoute('show_select', ['target' => 'summary_pdf']);
        }
        $summary = $em->getRepository('AppBundle:Show')->getShowSummary($show);
        $html = $this->renderView(
            'Pdf/showSummary.html.twig',
            [
                'artists' => $summary,
                'show' => $show,
                'reportTitle' => 'Summary for ' . $show->getShow(),
                ]
        );
        $filename = $show->getShow() . ' Summary';

//        return new PdfResponse(
//            $this->get('knp_snappy.pdf')->getOutputFromHtml($html),
//            $filename
//        );

        $exec = $pdf->pdfExecutable();
        $snappy = new Pdf($exec);
        $content = $snappy->getOutputFromHtml($html);
        $response = new Response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename=' . urlencode($filename) . '.pdf',
        ]);

        return $response;
    }

    /**
     * @Route("/test")
     */
    public function testAction(PdfService $pdf)
    {
        $html = $this->renderView('Pdf/test.html.twig');
        $filename = 'test';
        $exec = $pdf->pdfExecutable();
        $snappy = new Pdf($exec);
        $content = $snappy->getOutputFromHtml($html);
        $response = new Response($content, 200, [
            'Content-Type' => 'application/pdf',
//            'Content-Disposition' => 'attachment; filename=' . urlencode($filename) . '.pdf',
        ]);

        return $response;
    }
}
