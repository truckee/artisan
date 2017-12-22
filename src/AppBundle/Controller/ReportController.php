<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\ReportController.php

namespace AppBundle\Controller;

use AppBundle\Services\Defaults;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ReportController
 * @Route("/reports")
 *
 */
class ReportController extends Controller
{
    /**
     * @Route("/", name= "reports")
     */
    public function reportsPageAction()
    {
        return $this->render('Reports/reportPage.html.twig');
    }

    /**
     * @Route("/artistsInShow", name="report_artists_in_show")
     */
    public function viewShowArtistsAction(Request $request, Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('AppBundle:Artist')->allArtistsInShow($show);

        return $this->render('Artist/inShow.html.twig', [
                'artists' => $artists,
        ]);
    }

    /**
     * @Route("/showSummary/{id}", name="show_summary")
     */
    public function showSummaryAction(Request $request, $id = null)
    {
        if (null !== $id) {
            $em = $this->getDoctrine()->getManager();
            $show = $em->getRepository('AppBundle:Show')->find($id);
        } else {
            return $this->redirectToRoute('show_select', ['target' => 'summary']);
        }
        $summary = $em->getRepository('AppBundle:Show')->getShowSummary($show);

        return $this->render(
            'Show/showSummary.html.twig',
                [
                    'artists' => $summary,
                    'show' => $show,
        ]
        );
    }

    /**
     * @Route("/blocksByArtist", name="blocks_by_artist")
     */
    public function showBlocksByArtistAction(Request $request, Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $blocks = $em->getRepository('AppBundle:Block')->getBlocksByArtists($show);

        return $this->render(
            'Block/blocksByArtists.html.twig',
                [
                'blocks' => $blocks,
                'show' => $show,
        ]
        );
    }

    /**
     * @Route("/viewReceipts", name="receipt_view")
     */
    public function viewShowReceiptsAction(Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $receipts = $em->getRepository('AppBundle:Receipt')->findBy(['show' => $show], ['receiptNo' => 'ASC']);

        return $this->render('Receipt/viewShowReceipts.html.twig',
                [
                'receipts' => $receipts,
                'show' => $show,
        ]);
    }

    /**
     * @Route("/singleArtistTickets/{id}", name="single_artist_tickets")
     */
    public function viewSingleArtistTicketsAction(Defaults $defaults, $id = null)
    {
        $show = $defaults->showDefault();
        if (null !== $id) {
            $em = $this->getDoctrine()->getManager();
            $artist = $em->getRepository('AppBundle:Artist')->find($id);
            $tickets = $em->getRepository('AppBundle:Show')->getSingleArtist($show, $artist);
        } else {
            return $this->redirectToRoute('artist_select', ['target' => 'tickets']);
        }

        return $this->render(
            'Artist/singleArtistTickets.html.twig',
                [
                    'artist' => $artist,
                    'tickets' => $tickets,
                    'show' => $show,
        ]
        );
    }

    /**
     * @Route("/viewArtists", name="view_artists")
     */
    public function viewArtistsAction(Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('AppBundle:Artist')->allArtistsInShow($show);

        return $this->render('Artist/inShow.html.twig', [
            'artists' => $artists,
        ]);
    }

    /**
     * @Route("/ArtistsByBlock", name="blocks_by_block")
     */
    public function showArtistByBlocksAction(Request $request, Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $blocks = $em->getRepository('AppBundle:Block')->getBlocksByBlock($show);

        return $this->render(
            'Block/blocksByBlock.html.twig',
                [
                'blocks' => $blocks,
                'show' => $show,
        ]
        );
    }

    /**
     * @Route("/viewSingleReceipt/{id}", name="view_single_receipt")
     */
    public function viewSingleReceiptAction(Request $request, Defaults $defaults, $id = null)
    {
        $show = $defaults->showDefault();
        if (null === $id) {
            return $this->redirectToRoute('receipt_select', ['target' => 'single']);
        }
        $em = $this->getDoctrine()->getManager();
        $receipt = $em->getRepository('AppBundle:Receipt')->findOneBy(['receiptNo' => $id]);


        return $this->render('Receipt/viewSingleReceipt.html.twig',
                [
                'receipt' => $receipt,
                'show' => $show,
        ]);
    }
}