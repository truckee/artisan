<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\TicketController.php

namespace AppBundle\Controller;

use AppBundle\Form\TicketType;
use AppBundle\Services\Defaults;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * TicketController
 * @Route("/ticket")
 *
 */
class TicketController extends Controller
{
    /**
     * @Route("/edit/{id}", name="ticket_edit")
     */
    public function editTicketAction(Request $request, Defaults $defaults, $id)
    {
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('AppBundle:Ticket')->find($id);
        $form = $this->createForm(TicketType::class, $ticket, [
            'validation_groups' => ['edit'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton() && 'delete' === $form->getClickedButton()->getName()) {
                $flash->danger('delete');
            } else {
                $flash->success('valid');
            }
            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('Ticket/editTicket.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="ticket_delete")
     */
    public function deleteTicketAction(Request $request, Defaults $defaults, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $flash = $this->get('braincrafted_bootstrap.flash');
        $ticket = $em->getRepository('AppBundle:Ticket')->find($id);
        if (null === $ticket) {
            $flash->alert('Ticket does not exist');

            return $this->redirectToRoute('homepage');
        }
        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);
        if ($request->isMethod('POST')) {
            $artist = $ticket->getArtist();
            $artist->removeTicket($ticket);
            $em->persist($artist);
            $em->flush();
            $flash->alert('Ticket has been deleted');

            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('Ticket/deleteTicket.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket,
        ]);
    }
}
