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
use AppBundle\Form\AddExistingArtistsType;
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
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (is_null($show)) {
            $flash->error('Create a default show before adding an artist!');

            return $this->redirectToRoute("new_show");
        }
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $artist->addShow($show);
            $em->persist($artist);
            $em->flush();
            $flash->success('Artist added!');

            return $this->redirectToRoute("homepage");
        }
        $flash->error($form->getErrors());

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
        $form = $this->createForm(AddExistingArtistsType::class, $show, ['show' => $show]);
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (is_null($show)) {
            $flash->error('Create a default show before adding an artist!');

            return $this->redirectToRoute("new_show");
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $artists = $form->get('artists')->getData();
            $n = count($artists);
            foreach ($artists as $artist) {
                $artist->addShow($show);
                $em->persist($artist);
            }
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
                'Artist/existingArtist.html.twig',
                [
                'form' => $form->createView(),
                ]
        );
    }
    
    /**
     * @Route("/view", name="show_view")
     */
    public function viewShowArtists(Request $request, Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('AppBundle:Artist')->inShow($show);

        return $this->render('Artist/inShow.html.twig', [
            'artists' => $artists,
        ]);
        
    }
}
