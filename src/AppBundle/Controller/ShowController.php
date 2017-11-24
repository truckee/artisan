<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\ShowController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\Form\ShowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ShowController
 * @Route("/show")
 *
 */
class ShowController extends Controller
{

    /**
     *
     * @Route("/new", name="new_show")
     *
     */
    public function addShowAction(Request $request)
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
                $em->persist($show);
                $em->flush();
                $this->addFlash(
                    'notice', 'Show added!'
                );
                $this->redirectToRoute("homepage");
        }

        return $this->render('Show/newShow.html.twig',
                [
                    'form' => $form->createView(),
                ]
        );
    }

    /**
     * @Route("/block/{id}")
     */
    public function blockTestAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $show = $em->getRepository('AppBundle:Show')->find($id);
        $block= $this->getDoctrine()->getRepository(Show::class)->createBlock($show);

        return $this->render('Show/blockTest.html.twig', [
            'ticketblock' => $block
        ]);
}
}
