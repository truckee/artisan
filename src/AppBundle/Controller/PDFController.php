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
        $em = $this->getDoctrine()->getManager();
        if (null !== $id) {
            $show = $em->getRepository('AppBundle:Show')->find($id);
        } else {
            return $this->redirectToRoute('show_select', ['target' => 'summary_pdf']);
        }
        $receipts = $em->getRepository('AppBundle:Receipt')->nonzeroReceipts($show);
        if (empty($receipts)) {
            $flash->info('No receipts with > $0 for show');

            return $this->redirectToRoute('homepage');
        }
        $summary = $em->getRepository('AppBundle:Show')->getShowSummary($show);
        $taxfree = $em->getRepository('AppBundle:Show')->getShowNontaxable($show);

        $html = $this->renderView(
            'Pdf/Show/summaryContent.html.twig',
            [
                'artists' => $summary,
                'taxfree' => $taxfree,
                'show' => $show,
            ]
        );
        $header = $this->renderView('Pdf/Show/summaryHeader.html.twig',
            [
            'reportTitle' => 'Summary for ' . $show->getShow(),
        ]);
        $filename = $showName = str_replace(' ', '', $show->getShow()) . 'Summary' . '.pdf';

        $exec = $pdf->pdfExecutable();
        $snappy = new Pdf($exec);
        $snappy->setOption('header-html', $header);
        $content = $snappy->getOutputFromHtml($html);
        $response = new Response($content, 200,
            [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename=' . $filename,
        ]);

        return $response;
    }

    /**
     * @Route("/allTickets", name="all_tickets")
     */
    public function allTicketsAction(PdfService $pdf, Defaults $defaults)
    {
        if (!$defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }

        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();
        $receipts = $em->getRepository('AppBundle:Receipt')->nonzeroReceipts($show);
        if (empty($receipts)) {
            $flash->info('No receipts with > $0 for show');

            return $this->redirectToRoute('homepage');
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
            $header = $this->renderView('Pdf/Artist/pdfArtistsTicketsHeader.html.twig',
                [
                    'artist' => $artist['artist'],
                    'show' => $show,
            ]);
            $page = $this->renderView(('Pdf/Artist/pdfArtistsTickets.html.twig'),
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

        $response = new Response($binary, 200,
            [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename=' . $showName . "ArtistTickets.pdf",
        ]);

        return $response;
    }
}
