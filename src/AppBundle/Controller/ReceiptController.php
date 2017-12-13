<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\ReceiptController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Receipt;
use AppBundle\Services\TicketArtist;
use AppBundle\Services\TicketUsed;
use AppBundle\Form\ReceiptTicketType;
use AppBundle\Form\SelectReceiptType;
use AppBundle\Services\Defaults;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ReceiptController
 * @Route("/receipt")
 *
 */
class ReceiptController extends Controller
{

    /**
     * Used by receipt form to get artist name
     *
     * @Route("/findTicket/{ticket}", name="find_ticket")
     */
    public function findTicketAction(Request $request, TicketArtist $artist, $ticket)
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
     * @Route("/new", name="new_receipt")
     * @param Request $request
     * @param Defaults $defaults
     */
    public function newReceipt(Request $request, Defaults $defaults, TicketArtist $artist)
    {
        $receipt = new Receipt();
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Create a default show before adding a receipt!');

            return $this->redirectToRoute("new_show");
        }
        $em = $this->getDoctrine()->getManager();
        $nextId = $em->getRepository('AppBundle:Receipt')->getNewReceiptNo();
        $form = $this->createForm(ReceiptTicketType::class, $receipt, [
            'next' => $nextId,
            ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $tickets = $form->get('tickets')->getData();
                foreach ($tickets as $ticket) {
                    $ticketNumber = $ticket->getTicket();
                    $owner = $artist->getTicketArtist($ticketNumber);
                    $ticket->setArtist($owner);
                    $receipt->addTicket($ticket);
                }
                $receipt->setShow($show);

                $em->persist($receipt);
                $em->flush();
                $flash->success('Receipt added!');

                return $this->redirectToRoute("homepage");
            } else {
                $flash->error('At least one ticket is required');
            }
        }
        
        return $this->render(
                'Receipt/receiptForm.html.twig',
                [
                'form' => $form->createView(),
                'nextId' => $nextId,
                ]
        );
    }

    /**
     * @Route("/view", name="receipts_view")
     */
    public function viewShowReceipts(Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $receipts = $em->getRepository('AppBundle:Receipt')->findBy(['show' => $show], ['receiptNo' => 'ASC']);

        return $this->render('Receipt/viewShowReceipts.html.twig', [
            'receipts' => $receipts,
            'show' => $show,
        ]);
    }

    /**
     * @Route("/select", name="receipt_select")
     */
    public function selectReceipt(Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $receipt = new Receipt();
        $form = $this->createForm(SelectReceiptType::class, $receipt, ['show' => $show]);

        return $this->render('Receipt/selectReceipt.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="receipt_edit")
     */
    public function editReceiptAction(Request $request, Defaults $defaults, $id = null)
    {
        if (null !== $id) {
            $em = $this->getDoctrine()->getManager();
            $artist = $em->getRepository('AppBundle:Receipt')->find($id);
        } else {
            return $this->redirectToRoute('receipt_select');
        }
        $show = $defaults->showDefault();
        $em = $this->getDoctrine()->getManager();
        $receipt = $em->getRepository('AppBundle:Receipt')->findOneBy(['receiptNo' => $id]);

        return $this->render('Receipt/editReceipt.html.twig', [
            'receipt' => $receipt,
        ]);
    }
}
