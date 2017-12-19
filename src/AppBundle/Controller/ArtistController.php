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
use AppBundle\Form\SelectArtistType;
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
     * @Route("/new", name="artist_add")
     *
     */
    public function addArtistAction(Request $request, Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $artist = new Artist();
        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ArtistType::class, $artist,
            [
                'show' => $show,
                'entity_manager' => $em,
                'validation_groups' => ['add'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $inShow = ($form->has('inShow')) ? $form->get('inShow')->getData() : null;
            if (true === $inShow) {
                $artist->addShow($show);
            }
            $em->persist($artist);
            $em->flush();
            $flash->success('Artist added!');

            return $this->redirectToRoute("homepage");
        } else {
            $flash->error($form->getErrors());
        }

        return $this->render(
                'Artist/artistForm.html.twig',
                [
                    'form' => $form->createView(),
                    'action' => 'Add artist',
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
        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();
        $someNotInShow = $em->getRepository('AppBundle:Artist')->someNotInShow($show);
        if (0 === count($someNotInShow->getQuery()->getResult())) {
            $flash->info('All artists are participating in the show');

            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(AddExistingArtistsType::class, $show,
            [
                'show' => $show,
                'query_bulider' => $someNotInShow,
        ]);
        if (is_null($show)) {
            $flash->error('Create a default show before adding an artist!');

            return $this->redirectToRoute("show_add");
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
        $artists = $em->getRepository('AppBundle:Artist')->allArtistsInShow($show);

        return $this->render('Artist/inShow.html.twig', [
                'artists' => $artists,
        ]);
    }

    /**
     * @Route("/select/{target}", name="artist_select")
     */
    public function selectArtistAction(Request $request, $target)
    {
        $artist = new Artist();
        $form = $this->createForm(SelectArtistType::class, $artist, ['target' => $target]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $request->request->get('select_artist')['artist'];
            switch ($target) {
                case 'edit':
                    return $this->redirectToRoute('artist_edit', ['id' => $id]);
                case 'block':
                    return $this->redirectToRoute('block_add', ['id' => $id]);
                case 'tickets';
                    return $this->redirectToRoute('single_artist_tickets', ['id' => $id]);
                case 'block edit':
                    return $this->redirectToRoute('block_edit', ['id' => $id]);
                default:
                    break;
            }
        }

        return $this->render('default/selectEntity.html.twig',
                [
                    'form' => $form->createView(),
                    'artist' => $artist,
                    'heading' => 'Select artist for ' . $target,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="artist_edit")
     */
    public function editArtistAction(Request $request, Defaults $defaults, $id = null)
    {
        if (null !== $id) {
            $em = $this->getDoctrine()->getManager();
            $artist = $em->getRepository('AppBundle:Artist')->find($id);
        } else {
            return $this->redirectToRoute('artist_select', ['target' => 'edit']);
        }
        $show = $defaults->showDefault();
        $form = $this->createForm(ArtistType::class, $artist,
            [
                'show' => $show,
                'entity_manager' => $em,
                'validation_groups' => ['edit'],
        ]);
        $flash = $this->get('braincrafted_bootstrap.flash');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $inShow = ($form->has('inShow')) ? $form->get('inShow')->getData() : null;
            $already = $show->getArtists()->contains($artist);
            if (true === $inShow && !$already) {
                $artist->addShow($show);
            } elseif (false === $inShow && $already) {
                $artist->removeShow($show);
            }
            $em->persist($artist);
            $em->flush();
            $flash->success($artist->getFirstName() . ' ' . $artist->getLastName() . ' updated!');

            return $this->redirectToRoute("homepage");
        } else {
            $flash->error($form->getErrors());
        }

        return $this->render('Artist/artistForm.html.twig',
                [
                    'form' => $form->createView(),
                    'artist' => $artist,
                    'action' => 'Edit artist',
        ]);
    }

    /**
     * @Route("/tickets/{id}", name="single_artist_tickets")
     */
    public function viewSingleArtistTickets(Request $request, Defaults $defaults, $id = null)
    {
        $show = $defaults->showDefault();
        if (null !== $id) {
            $em = $this->getDoctrine()->getManager();
            $artist = $em->getRepository('AppBundle:Artist')->find($id);
            $tickets = $em->getRepository('AppBundle:Show')->getSingleArtist($show, $artist);
        } else {
            return $this->redirectToRoute('artist_select', ['target' => 'tickets']);
        }

        return $this->render('Artist/singleArtistTickets.html.twig',
                [
                    'artist' => $artist,
                    'tickets' => $tickets,
                    'show' => $show,
        ]);
    }
}
