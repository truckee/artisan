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
use AppBundle\Form\TicketSearchType;
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
        $em = $this->getDoctrine()->getManager();
        $receipt = $em->getRepository('AppBundle:Receipt')->findOneBy(['id' => $id]);
        $ticket = new Ticket();
        //set artist's total ticket amount for this receipt - required for validation
        $ticketNumber = $request->request->get('ticket')['ticket'];
        $total = null;
        if (null !== $ticketNumber) {
            $artist = $owner->getTicketArtist($ticketNumber);
            $total = $em->getRepository('AppBundle:Receipt')->receiptArtistTotal($receipt, $artist);

            $ticket->setRcptTotal($total);
        }
        $form = $this->createForm(TicketType::class, $ticket,
            [
                'validation_groups' => ['add'],
                'cancel_action' => $this->generateUrl('homepage'),
                'rcptTotal' => $total,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

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
    public function editTicketAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $em->getRepository('AppBundle:Ticket')->find($id);
        //set artist's total ticket amount for this receipt - required for validation
        $artist = $ticket->getArtist();
        $receipt = $ticket->getReceipt();
        $total = $em->getRepository('AppBundle:Receipt')->receiptArtistTotal($receipt, $artist);
        $ticket->setRcptTotal($total);
        $form = $this->createForm(TicketType::class, $ticket,
            [
                'validation_groups' => ['edit'],
                'cancel_action' => $this->generateUrl('homepage'),
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

    /**
     * @Route("/search/{number}", name="ticket_search")
     */
    public function searchTicketAction(Request $request, $number = null)
    {
        $ticket = new Ticket();
        $form = $this->createForm(TicketSearchType::class, $ticket,
            [
                'cancel_action' => $this->generateUrl('homepage'),
        ]);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $number = $form->get('ticket')->getData();
            $em = $this->getDoctrine()->getManager();
            $tickets = $em->getRepository('AppBundle:Ticket')->searchTickets($number);
            $flash = $this->get('braincrafted_bootstrap.flash');
            if (empty($tickets)) {
                $flash->info('Ticket ' . $number . ' not found');

                return $this->redirectToRoute('homepage');
            }
            return $this->render('Ticket/ticketsFound.html.twig', [
                'tickets' => $tickets,
                'number' => $number
            ]);
        }
        

        return $this->render(
                'Ticket/ticketSearchForm.html.twig',
                [
                    'form' => $form->createView(),
                    'ticket' => $number,
                ]);
     }
}
