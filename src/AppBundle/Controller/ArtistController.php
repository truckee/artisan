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
use AppBundle\Form\ShowArtistsType;
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
        $show = $defaults->showDefault();
        $artist = new Artist();
        if (is_null($show)) {
            $this->addFlash(
                'notice', 'Create a default show before adding an artist!'
            );

            return $this->redirectToRoute("new_show");
        }
        $form = $this->createForm(ArtistType::class, $artist);
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
                'Artist/newArtist.html.twig', [
                'form' => $form->createView(),
                ]
        );
    }

    /**
     * @Route("/existing", name="existing_artists")
     */
    public function addExistingArtistsAction(Request $request, Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $artist = new Artist();
        $form = $this->createForm(ShowArtistsType::class, $show, ['show' => $show]);
        if (is_null($show)) {
            $this->addFlash(
                'notice', 'Create a default show before adding an artist!'
            );

            return $this->redirectToRoute("new_show");
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $artists = $form->get('artists')->getData();
            $n = 0;
            foreach ($artists as $artist) {
                $artist->addShow($show);
                $em->persist($artist);
                $n ++;
            }
            $flash = $this->get('braincrafted_bootstrap.flash');
            if (0 === $n) {
                $flash->info('No artist added!');

                return $this->redirectToRoute("existing_artists");
            } else {
                $em->flush();
                $qty = (1 === $n) ? 'Artist ' : $n . ' artists';
                $flash->success($qty . ' added!');
            }

            return $this->redirectToRoute("homepage");
        }

        return $this->render(
                'Artist/existingArtist.html.twig', [
                    'form' => $form->createView(),
                ]
        );
    }
}
