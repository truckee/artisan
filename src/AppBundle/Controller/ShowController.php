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
        $em = $this->getDoctrine()->getManager();
        $default = $em->getRepository('AppBundle:Show')->findOneBy(['default' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            //set other default show to not be default
            if (true == $form->getData()->isDefault() && !is_null($default)) {
                
                $default->setDefault(false);
                $em->persist($default);
            }
            $em->persist($show);
            $em->flush();
            $this->addFlash(
                'notice',
                'Show added!'
            );
            $this->redirectToRoute("homepage");
        }

        return $this->render(
            'Show/newShow.html.twig',
                [
                    'form' => $form->createView(),
                ]
        );
    }
}
