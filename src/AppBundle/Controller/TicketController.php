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

use AppBundle\Entity\Ticket;
use AppBundle\Form\TicketType;
use AppBundle\Services\Defaults;
use AppBundle\Services\TicketArtist;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TicketController
 * @Route("/ticket")
 *
 */
class TicketController extends Controller
{

    /**
     * @Route("/add/{id}", name="ticket_add")
     */
    public function addTicketAction(Request $request, TicketArtist $owner, $id)
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class, $ticket, [
            'validation_groups' => ['add'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $receipt = $em->getRepository('AppBundle:Receipt')->findOneBy(['id' => $id]);
            $artist = $owner->getTicketArtist($ticket->getTicket());
            $ticket->setArtist($artist);
            $ticket->setReceipt($receipt);
            $em->persist($ticket);
            $em->flush();
            $view = $this->renderView('Ticket/ticketEditRow.html.twig',
                [
                    'ticket' => $ticket,
            ]);
            $response = new Response((json_encode($view, JSON_HEX_QUOT | JSON_HEX_TAG)));

            return $response;
        }

        return $this->render('Ticket/ticketAddDialogForm.html.twig',
                [
                    'form' => $form->createView(),
                    'ticket' => $ticket,
                    'id' => $id,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="ticket_edit")
     */
    public function editTicketAction(Request $request, Defaults $defaults, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('AppBundle:Ticket')->find($id);
        $form = $this->createForm(TicketType::class, $ticket, [
            'validation_groups' => ['edit'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($ticket);
            $em->flush();
            $response = new Response($ticket->getAmount());

            return $response;
        }

        return $this->render('Ticket/ticketEditDialogForm.html.twig',
                [
                    'form' => $form->createView(),
                    'ticket' => $ticket,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="ticket_delete")
     */
    public function deleteTicketAction(Request $request, $id)
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

        return $this->render('Ticket/deleteTicket.html.twig',
                [
                    'form' => $form->createView(),
                    'ticket' => $ticket,
        ]);
    }

    /**
     * Used by receipt form to get artist name
     *
     * @Route("/findTicket/{ticket}", name="find_ticket")
     */
    public function findTicketAction(TicketArtist $artist, $ticket)
    {
        $entity = $artist->getTicketArtist($ticket);
        if (null === $entity) {
            $response = new Response('Ticket does not exist');

            return $response;
        }
        $name = $entity->getFirstName() . ' ' . $entity->getLastName();
        $response = new Response($name);

        return $response;
    }
}
