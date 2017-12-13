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
        $form = $this->createForm(TicketType::class, $ticket,[
            'validation_groups' => ['edit'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getClickedButton() && 'delete' === $form->getClickedButton()->getName()) {
                $flash->danger('delete');
            }  else {
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
}
