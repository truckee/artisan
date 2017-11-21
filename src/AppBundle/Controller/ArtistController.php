<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\ArtistController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use AppBundle\Form\ArtistType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ArtistController
 * @Route("/artist")
 *
 */
class ArtistController extends Controller
{

    /**
     *
     * @Route("/new", name="new_artist")
     *
     */
    public function addArtistAction(Request $request)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $nameArray = ['firstName' => $form->getData()->getFirstName(), 'lastName' => $form->getData()->getLastName()];
            $em = $this->getDoctrine()->getManager();
            $nameExists = $em->getRepository('AppBundle:Artist')->findOneBy($nameArray);
            if (null === $nameExists) {
                $em->persist($artist);
                $em->flush();
                $this->addFlash(
                    'notice', 'Artist added!'
                );
                $this->redirectToRoute("homepage");
            } else {
                $this->addFlash(
                    'notice', 'Artist already exists!'
                );               
            }
        }

        return $this->render('Artist/newArtist.html.twig',
                [
                    'form' => $form->createView(),
                ]
        );
    }
}
