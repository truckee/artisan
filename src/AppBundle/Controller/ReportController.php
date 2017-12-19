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
    public function viewShowArtists(Request $request, Defaults $defaults)
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
}
