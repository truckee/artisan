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
use AppBundle\Services\Defaults;
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
    public function addArtistAction(Request $request, Defaults $defaults)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $default = $defaults->showDefault();
        if (is_null($default)) {
            $this->addFlash(
                'notice', 'Create a default show before adding an artist!'
            );

            return $this->redirectToRoute("new_show");
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();
            $this->addFlash(
                'notice', 'Artist added!'
            );

            return $this->redirectToRoute("homepage");
        }

        return $this->render(
                'Artist/newArtist.html.twig',
                [
                    'form' => $form->createView(),
                    'defaultShow' => $default->getShow(),
                ]
        );
    }
}
