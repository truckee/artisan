<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\BlockController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Block;
use AppBundle\Form\BlockType;
use AppBundle\Form\SelectBlockType;
use AppBundle\Services\Defaults;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * BlockController
 * @Route("/block")
 */
class BlockController extends Controller
{

    /**
     * @Route("/new/{id}", name="new_block")
     * @param Request $request
     * @param Defaults $defaults
     */
    public function addBlockAction(Request $request, Defaults $defaults, $id = null)
    {
        $block = new Block();
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Create a default show before adding a ticket block!');

            return $this->redirectToRoute('homepage');
        }
        if (null === $id) {
            $flash->error('Select an artist before adding a ticket block!');

            return $this->redirectToRoute('artist_select', ['target' => 'block']);
        }
        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('AppBundle:Artist')->find($id);
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $block->setArtist($artist);
            $block->setShow($show);
            $em->persist($block);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Block added!');

            return $this->redirectToRoute('homepage');
        } else {
            $flash->error($form->getErrors());
        }

        return $this->render('Block/blockForm.html.twig',
                [
                    'form' => $form->createView(),
                    'artist' => $artist,
                    'action' => 'Add'
        ]);
    }

    /**
     * @Route("/edit/{id}/{blockId}", name="edit_block")
     * @param Request $request
     * @param Defaults $defaults
     */
    public function editBlockAction(Request $request, Defaults $defaults, $id = null, $blockId = null)
    {
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Create a default show before adding a ticket block!');

            return $this->redirectToRoute('homepage');
        }
        if (null === $id) {
            $flash->error('Select an artist before editing a ticket block!');

            return $this->redirectToRoute('artist_select', ['target' => 'block edit']);
        }
        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('AppBundle:Artist')->find($id);
        if (null === $blockId) {
            $flash->error('Select block to edit');

            return $this->redirectToRoute('block_select',
                    [
                        'artist' => $id,
                        'show' => $show->getId(),
            ]);
        }
        $block = $em->getRepository('AppBundle:Block')->find($blockId);
        $form = $this->createForm(BlockType::class, $block, [
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($block);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Block updated!');

            return $this->redirectToRoute('homepage');
        } else {
            $flash->error($form->getErrors());
        }

        return $this->render('Block/blockForm.html.twig',
                [
                    'form' => $form->createView(),
                    'block' => $block,
                    'artist' => $artist,
                    'action' => 'Edit',
        ]);
    }

    /**
     * @Route("/select/{artist}/{show}", name="block_select")
     */
    public function selectBlockAction(Request $request, $artist, $show)
    {
        $block = new Block();
        $em = $this->getDoctrine()->getManager();
        $owner = $em->getRepository('AppBundle:Artist')->find($artist);
        $form = $this->createForm(SelectBlockType::class, $block,
            [
            'artist' => $artist,
            'show' => $show,
        ]);

        return $this->render('Block/selectBlock.html.twig',
                [
                    'form' => $form->createView(),
                    'block' => $block,
                    'artist' => $owner,
        ]);
    }
}
