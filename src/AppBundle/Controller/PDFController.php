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

use AppBundle\Services\Defaults;
use AppBundle\Services\PdfService;
//use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use PDFMerger;
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

        $exec = $pdf->pdfExecutable();
        $snappy = new Pdf($exec);
        $content = $snappy->getOutputFromHtml($html);
        $response = new Response($content, 200,
            [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename=' . urlencode($filename) . '.pdf',
        ]);

        return $response;
    }

    /**
     * @Route("/allTickets", name="all_tickets")
     */
    public function allTicketsAction(PdfService $pdf, Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Set a show to active before creating artists list!');

            return $this->redirectToRoute("homepage");
        }
        $em = $this->getDoctrine()->getManager();
        $showArtists = $em->getRepository('AppBundle:Artist')->artistShowTickets($show);
        if (null === $showArtists) {
            $flash->error('No artists in show!');

            return $this->redirectToRoute("homepage");
        }

        $exec = $pdf->pdfExecutable();
        $snappy = new Pdf($exec);
        $dir = $this->getParameter('kernel.project_dir') . '/web/files/';
        $collection = new PDFMerger();
        $showName = str_replace(' ', '', $show->getShow());
        $trash = [];
        // remove time limit - this can take a while
        set_time_limit(0);
        foreach ($showArtists as $artist) {
            $filename = $dir . $showName . $artist['artist']->getFirstName() . $artist['artist']->getLastName() . '.pdf';
            $header = $this->renderView('Pdf/pdfArtistPageHeader.html.twig',
                [
                    'artist' => $artist['artist'],
                    'show' => $show,
            ]);
            $page = $this->renderView(('Pdf/pdfArtistTickets.html.twig'),
                [
                    'tickets' => $artist['tickets'],
                    'show' => $show,
            ]);
            $snappy->setOption('header-html', $header);
            file_put_contents($filename, $snappy->getOutputFromHtml($page));
            $collection->addPDF($filename);
            $trash[] = $filename;
        }

        $binary = $collection->merge('string', $showName . "ArtistTickets.pdf");
        //remove constituent files
        foreach ($trash as $discard) {
            unlink($discard);
        }

        $response = new Response($binary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename=' . $showName . "ArtistTickets.pdf",
        ]);

        return $response;
    }
}
