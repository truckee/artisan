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
    public function viewShowArtistsAction(Defaults $defaults)
    {
        if (false === $defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->hasArtistsInActiveShow()) {
            return $this->redirectToRoute('homepage');
        }

        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('AppBundle:Artist')->allArtistsInShow($show);

        return $this->render('Artist/inShow.html.twig', [
                'artists' => $artists,
        ]);
    }

    /**
     * @Route("/showSummary/{id}", name="show_summary")
     */
    public function showSummaryAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        if (null !== $id) {
            $show = $em->getRepository('AppBundle:Show')->find($id);
        } else {
            return $this->redirectToRoute('show_select', ['target' => 'summary']);
        }
        $summary = $em->getRepository('AppBundle:Show')->getShowSummary($show);
        $taxfree = $em->getRepository('AppBundle:Show')->getShowNontaxable($show);

        return $this->render(
                'Show/showSummary.html.twig',
                [
                    'artists' => $summary,
                    'taxfree' => $taxfree,
                    'show' => $show,
                ]
        );
    }

    /**
     * @Route("/blocksByArtist", name="blocks_by_artist")
     */
    public function showBlocksByArtistAction(Defaults $defaults)
    {
        if (false === $defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->hasArtistsInActiveShow()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->showHasBlocks()) {
            return $this->redirectToRoute('homepage');
        }

        $em = $this->getDoctrine()->getManager();
        $show = $defaults->showDefault();
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
        if (false === $defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->hasReceiptsInActiveShow()) {
            return $this->redirectToRoute('homepage');
        }

        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $receipts = $em->getRepository('AppBundle:Receipt')->receiptsInShow($show);

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
        if (false === $defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->hasArtistsInActiveShow()) {
            return $this->redirectToRoute('homepage');
        }

        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        if (null !== $id) {
            $artist = $em->getRepository('AppBundle:Artist')->find($id);
            if (false === $defaults->artistHasBlocksInShow($artist)) {
                return $this->redirectToRoute('homepage');
            }
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
        if (false === $defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->hasArtistsInActiveShow()) {
            return $this->redirectToRoute('homepage');
        }

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
    public function showArtistByBlocksAction(Defaults $defaults)
    {
        if (false === $defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->hasArtistsInActiveShow()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->showHasBlocks()) {
            return $this->redirectToRoute('homepage');
        }

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
    public function viewSingleReceiptAction(Defaults $defaults, $id = null)
    {
        if (false === $defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }
        if (false === $defaults->hasReceiptsInActiveShow()) {
            return $this->redirectToRoute('homepage');
        }

        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        if (null === $id) {
            return $this->redirectToRoute('receipt_select', ['target' => 'single']);
        }
        $receipt = $em->getRepository('AppBundle:Receipt')->findOneBy(['id' => $id]);

        return $this->render('Receipt/viewSingleReceipt.html.twig',
                [
                    'receipt' => $receipt,
                    'show' => $show,
        ]);
    }
}
