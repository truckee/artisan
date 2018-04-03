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
use AppBundle\Form\ReceiptType;
use AppBundle\Form\SelectReceiptType;
use AppBundle\Services\Defaults;
use AppBundle\Services\TicketArtist;
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
     * @Route("/add", name="receipt_add")
     */
    public function addReceipt(Defaults $defaults)
    {
        $receipt = new Receipt();
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Set a show to active before adding a receipt!');

            return $this->redirectToRoute("homepage");
        }
        $em = $this->getDoctrine()->getManager();
        //save receipt to guarantee uniqueness
        $receipt->setSalesDate(new \DateTime());
        $receipt->setShow($show);
        $em->persist($receipt);
        $em->flush();

        return $this->redirectToRoute('receipt_edit',
                [
                    'id' => $receipt->getId(),
                    'add' => true,
        ]);
    }

    /**
     * @Route("/select/{target}", name="receipt_select")
     */
    public function selectReceipt(Request $request, Defaults $defaults, $target)
    {
        $show = $defaults->showDefault();
        $form = $this->createForm(SelectReceiptType::class, null,
            [
            'target' => $target,
            'cancel_action' => $this->generateUrl('homepage'),
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $id = $request->request->get('select_receipt')['receipt'];
            switch ($target) {
                case 'edit':
                    return $this->redirectToRoute('receipt_edit', ['id' => $id]);
                case 'single':
                    return $this->redirectToRoute('view_single_receipt', ['id' => $id]);
                case 'add_nontaxable':
                    return $this->redirectToRoute('nontax_add', ['id' => $id]);
                default:
                    break;
            }
        }

        return $this->render('default/selectEntity.html.twig',
                [
                    'form' => $form->createView(),
                    'heading' => 'Select receipt',
        ]);
    }

    /**
     * @Route("/edit/{id}", name="receipt_edit")
     */
    public function editReceiptAction(Request $request, Defaults $defaults, $id = null)
    {
        $show = $defaults->showDefault();
        $flash = $this->get('braincrafted_bootstrap.flash');
        if (null === $show) {
            $flash->error('Set a show to active before editing a receipt!');

            return $this->redirectToRoute("homepage");
        }
        if (null === $id) {
            return $this->redirectToRoute('receipt_select', ['target' => 'edit']);
        }
        $em = $this->getDoctrine()->getManager();
        $receipt = $em->getRepository('AppBundle:Receipt')->findOneBy(['id' => $id]);
        $form = $this->createForm(ReceiptType::class, $receipt, [
            'save_label' => 'Save',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($receipt);
            $em->flush();
            if ($form->get('view')->isClicked()) {
                return $this->redirectToRoute('view_single_receipt', ['id' => $id]);
            }
            $flash->success('Receipt updated!');

            return $this->redirectToRoute("homepage");
        }

        return $this->render('Receipt/receiptEditForm.html.twig',
                [
                    'receipt' => $receipt,
                    'form' => $form->createView(),
        ]);
    }
}
