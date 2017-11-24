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

use AppBundle\Entity\Ticket;
use AppBundle\Form\ReceiptTicketType;
use AppBundle\Services\Defaults;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ReceiptController
 * @Route("/receipt")
 *
 */
class ReceiptController extends Controller
{

    /**
     * @Route("/findTicket", name="find_ticket")
     */
    public function findTicketAction(Request $request, Defaults $defaults)
    {
        $ticket = new Ticket();
        $form = $this->createForm(ReceiptTicketType::class, $ticket);
        $show = $defaults->showDefault();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $ticket = $form->getData()->getTicketnumber();
            $em = $this->getDoctrine()->getManager();
            $blocks = $em->getRepository('AppBundle\Entity\Block')->findBy(['show' => $show]);
            foreach ($blocks as $value) {
                $block = $value->getBlock();
                if ($block[0] <= $ticket && $ticket <= $block[1]) {
                    $artist = $value->getArtist();
                    $this->addFlash(
                        'notice', 'Artist found: ' . $artist->getFirstName() . ' ' . $artist->getLastName()
                    );
                } else {
                    $this->addFlash(
                        'notice', 'Ticket not found!'
                    );
                }
            }
        }

        return $this->render('Receipt/findTicket.html.twig', [
                    'form' => $form->createView(),
        ]);
    }
}
