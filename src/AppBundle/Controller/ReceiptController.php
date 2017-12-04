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
            $flash = $this->get('braincrafted_bootstrap.flash');
            foreach ($blocks as $value) {
                $block = $value->getBlock();
                if ($block[0] <= $ticket && $ticket <= $block[1]) {
                    $artist = $value->getArtist();
                    $flash->success('Artist found: ' . $artist->getFirstName() . ' ' . $artist->getLastName());
                } else {
                    $flash->error('Ticket not found!');
                }
            }
        }

        return $this->render('Receipt/findTicket.html.twig',
                [
                'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="new_receipt")
     * @param Request $request
     * @param Defaults $defaults
     */
    public function newReceipt(Request $request, Defaults $defaults)
    {
        $receipt = new Receipt();
        $default = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (is_null($default)) {
            $flash->error('Create a default show before adding a receipt!');

            return $this->redirectToRoute("new_show");
        }
        $form = $this->createForm(ReceiptTicketType::class, $receipt);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $default = $defaults->showDefault();
            $em = $this->getDoctrine()->getManager();
            $em->persist($receipt);
            $em->flush();
            $flash->success('Receipt added!');

            return $this->redirectToRoute("homepage");
        }

        return $this->render(
                'Receipt/newReceipt.html.twig', [
                    'form' => $form->createView(),
                ]
        );
    }
}
