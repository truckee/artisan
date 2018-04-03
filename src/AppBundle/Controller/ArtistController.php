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
use Symfony\Component\HttpFoundation\Response;

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
        $form = $this->createForm(
            ArtistType::class, $artist,
            [
                'entity_manager' => $em,
                'validation_groups' => ['add'],
                'cancel_action' => $this->generateUrl('homepage'),
            ]
        );
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
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Set a show to active before adding an artist!');

            return $this->redirectToRoute("homepage");
        }
        $artist = new Artist();
        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();
        $someNotInShow = $em->getRepository('AppBundle:Artist')->someNotInShow($show);
        if (0 === count($someNotInShow->getQuery()->getResult())) {
            $flash->info('All artists are participating in the show');

            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(
            AddExistingArtistsType::class, $show,
            [
            'query_bulider' => $someNotInShow,
            'cancel_action' => $this->generateUrl('homepage'),
            ]
        );
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
     * @Route("/select/{target}/{notId}/{blockId}", name="artist_select")
     */
    public function selectArtistAction(Request $request, $target, $notId = null, $blockId = null)
    {
        $artist = new Artist();
        $form = $this->createForm(SelectArtistType::class, $artist,
            [
                'target' => $target,
                'notId' => $notId,
                'blockId' => $blockId,
                'cancel_action' => $this->generateUrl('homepage'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $request->request->get('select_artist')['artist'];
            switch ($target) {
                case 'edit':
                    return $this->redirectToRoute('artist_edit', ['id' => $id]);
                case 'block':
                    return $this->redirectToRoute('block_add', ['id' => $id]);
                case 'tickets':
                    return $this->redirectToRoute('single_artist_tickets', ['id' => $id]);
                case 'block edit':
                    return $this->redirectToRoute('block_select', ['id' => $id, 'action' => 'block_edit']);
                case 'block reassign':
                    return $this->redirectToRoute('block_select', ['id' => $id, 'action' => 'block_reassign']);
                case 'replacement':
                    $blockId = $request->request->get('select_artist')['blockId'];
                    return $this->redirectToRoute('block_reassign',
                            [
                                'replacementId' => $id,
                                'id' => $blockId,
                    ]);
                default:
                    break;
            }
        }

        return $this->render(
                'default/selectEntity.html.twig',
                [
                    'form' => $form->createView(),
                    'artist' => $artist,
                    'heading' => 'Select artist for ' . $target,
                ]
        );
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
        $form = $this->createForm(
            ArtistType::class, $artist,
            [
                'entity_manager' => $em,
                'validation_groups' => ['edit'],
                'cancel_action' => $this->generateUrl('homepage'),
            ]
        );
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

        return $this->render(
                'Artist/artistForm.html.twig',
                [
                    'form' => $form->createView(),
                    'artist' => $artist,
                    'action' => 'Edit artist',
                ]
        );
    }

    /**
     * @Route("/xml", name="artists_xml")
     */
    public function allArtistsXMLAction(Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Set a show to active before creating artists list!');

            return $this->redirectToRoute("homepage");
        }
        $em = $this->getDoctrine()->getManager();
        $showArtists = $em->getRepository('AppBundle:Artist')->artistShowTickets($show);
        if (null === $showArtists) {
            $flash->error('No artists in show!');

            return $this->redirectToRoute("homepage");
        }
        $content = $this->renderView('Artist/allArtists.xml.twig',
            [
            'showArtists' => $showArtists,
        ]);
        $filename = $show->getShow();
        $response = new Response($content, 200,
            [
            'Content-Type' => 'application/xml; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . urlencode($filename)
            ]
        );

        return $response;
    }

    /**
     * @Route("/delete")
     */
    public function deleteableArtistAction()
    {
        $em = $this->getDoctrine()->getManager();
        $artists = $em->getRepository('AppBundle:Artist')->deleteableArtists();

        return new Response('good');
        
    }
}
