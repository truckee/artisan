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
     * @Route("/new/{id}", name="block_add")
     * @param Request $request
     * @param Defaults $defaults
     */
    public function addBlockAction(Request $request, Defaults $defaults, $id = null)
    {
        $block = new Block();
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Set a show to active before adding a ticket block!');

            return $this->redirectToRoute('homepage');
        }
        if (null === $id) {
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

        return $this->render(
            'Block/blockForm.html.twig',
                [
                    'form' => $form->createView(),
                    'artist' => $artist,
                    'action' => 'Add'
        ]
        );
    }

    /**
     * @Route("/edit/{id}", name="block_edit")
     * @param Request $request
     * @param Defaults $defaults
     */
    public function editBlockAction(Request $request, Defaults $defaults, $id = null)
    {
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Set a show to active before editing a ticket block!');

            return $this->redirectToRoute('homepage');
        }
        if (null === $id) {
            return $this->redirectToRoute('artist_select', ['target' => 'block edit']);
        }
        $em = $this->getDoctrine()->getManager();
        $block = $em->getRepository('AppBundle:Block')->find($id);
        $form = $this->createForm(BlockType::class, $block);
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

        return $this->render(
            'Block/editBlock.html.twig',
                [
                    'form' => $form->createView(),
                    'block' => $block,
//                    'artist' => $artist,
                    'action' => 'Edit',
        ]
        );
    }

    /**
     * @Route("/reassign/{id}/{replacementId}", name="block_reassign")
     */
    public function reassignBlockAction(Request $request, Defaults $defaults, $id = null, $replacementId = null)
    {
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Set a show to active before reassigning a ticket block!');

            return $this->redirectToRoute('homepage');
        }
        //get id of block to reassign
        if (null === $id) {
            return $this->redirectToRoute('artist_select', ['target' => 'block reassign']);
        }

        $em = $this->getDoctrine()->getManager();
        $block = $em->getRepository('AppBundle:Block')->find($id);
        $artist = $block->getArtist();
        $notId = $artist->getId();
        if (null === $replacementId) {
            return $this->redirectToRoute('artist_select', [
                'target' => 'replacement',
                'blockId' => $id,
                'notId' => $notId,
                ]);
        }
        //replace artist
        $newOwner = $em->getRepository('AppBundle:Artist')->find($replacementId);
        $block->setArtist($newOwner);
        $em->persist($block);

        $count = 0;
        $receipts = $em->getRepository('AppBundle:Receipt')->findBy(['show' => $show]);
        foreach ($receipts as $receipt) {
            $tickets = $receipt->getTickets();
            foreach ($tickets as $ticket) {
                $number = $ticket->getTicket();
                if ($number >= $block->getLower() && $number <= $block->getUpper()) {
                    $ticket->setArtist($newOwner);
                    $em->persist($ticket);
                    $count ++;
                }
            }
        }
        $em->flush();

        $flash->success('Ticket block, ' .  $count . ' tickets reassigned to artist ' . $newOwner->getFirstName() . ' ' . $newOwner->getLastName());

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/select/{id}/{action}", name="block_select")
     */
    public function selectBlockAction(Request $request, $id, $action)
    {
        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('AppBundle:Artist')->find($id);
        $form = $this->createForm(
            SelectBlockType::class,
            null,
            [
                'artist' => $artist,
        ]
        );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $id = $request->request->get('select_block')['block'];

            return $this->redirectToRoute($action, ['id' => $id]);
        }

        return $this->render(
            'default/selectEntity.html.twig',
                [
                    'form' => $form->createView(),
                    'heading' => 'Select block for ' . $artist->getFirstName() . ' ' . $artist->getLastName(),
        ]
        );
    }

    /**
     * @Route("/delete/{id}", name="block_delete")
     */
    public function deleteBlockAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $block = $em->getRepository('AppBundle:Block')->find($id);
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $artist = $block->getArtist();
            $artist->removeBlock($block);
            $em->persist($artist);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Block deleted!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('Block/deleteBlock.html.twig', [
            'block' => $block,
            'form' => $form->createView(),
            ]);
    }
}
