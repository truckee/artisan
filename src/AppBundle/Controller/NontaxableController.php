<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\NontaxableController.php

namespace AppBundle\Controller;

use AppBundle\Form\NontaxableType;
use AppBundle\Entity\Nontaxable;
use AppBundle\Services\Defaults;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * NontaxableController
 *
 * @Route("/nontax")
 */
class NontaxableController extends Controller
{

    /**
     * @Route("/addAmount/{id}", name="nontax_add")
     */
    public function addAmountAction(Request $request, Defaults $defaults, $id)
    {
        if (!$defaults->isActiveShowSet()) {
            return $this->redirectToRoute('homepage');
        }

        if (null === $id) {
            return $this->redirectToRoute('receipt_select', ['target' => 'add_nontaxable']);
        }
        $nontax = new Nontaxable();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(NontaxableType::class, $nontax,
            [
                'validation_groups' => ['add'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $receipt = $em->getRepository('AppBundle:Receipt')->find($id);
            $em->persist($nontax);
            $receipt->setNontaxable($nontax);
            $em->persist($receipt);
            $em->flush();
            $view = $this->renderView('Nontaxable/notaxEditRow.html.twig',
                [
                    'receipt' => $receipt,
            ]);
            $response = new Response((json_encode($view, JSON_HEX_QUOT | JSON_HEX_TAG)));

            return $response;
        }

        return $this->render('Nontaxable/notaxDialogForm.html.twig',
                [
                    'form' => $form->createView(),
                    'id' => $id,
        ]);
    }

    /**
     * @Route("/editAmount/{id}", name="nontax_edit")
     */
    public function editAmountAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $nontax = $em->getRepository('AppBundle:Nontaxable')->find($id);
        $form = $this->createForm(NontaxableType::class, $nontax,
            [
                'validation_groups' => ['edit'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (0 !== (int) $nontax->getAmount()) {
                $em->persist($nontax);
            $response = new Response($nontax->getAmount());
            } else {
                $receipt = $nontax->getReceipt();
                $receipt->setNontaxable(null);
                $em->remove($nontax);
            $response = new Response(0);
            }
            $em->flush();

            return $response;
        }

        return $this->render('Nontaxable/notaxDialogForm.html.twig',
                [
                    'form' => $form->createView(),
                    'id' => $id,
        ]);
    }
}
