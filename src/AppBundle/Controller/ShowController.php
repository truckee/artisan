<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\ShowController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Show;
use AppBundle\Form\ShowType;
use AppBundle\Form\SelectShowType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ShowController
 * @Route("/show")
 *
 */
class ShowController extends Controller
{

    /**
     *
     * @Route("/new", name="new_show")
     *
     */
    public function addShowAction(Request $request)
    {
        $show = new Show();
        $form = $this->createForm(ShowType::class, $show);
        $em = $this->getDoctrine()->getManager();
        $default = $em->getRepository('AppBundle:Show')->findOneBy(['default' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $flash = $this->get('braincrafted_bootstrap.flash');
            //set other default show to not be default
            if (true == $form->getData()->isDefault() && !is_null($default)) {
                $default->setDefault(false);
                $em->persist($default);
            }
            $em->persist($show);
            $em->flush();
            $flash->success('Show added!');

            return $this->redirectToRoute("homepage");
        }

        return $this->render(
                'Show/showForm.html.twig',
                [
                    'form' => $form->createView(),
                    'action' => 'Add show',
                ]
        );
    }

    /**
     * @Route("/select", name="show_select")
     */
    public function selectShowForEdit()
    {
        $show = new Show();
        $form = $this->createForm(SelectShowType::class, $show);

        return $this->render('Show/selectShow.html.twig',
                [
                    'form' => $form->createView(),
                    'show' => $show
        ]);
    }

    /**
     * @Route("/edit/{id}", name="show_edit")
     */
    public function editShowAction(Request $request, $id = null)
    {
        if (null !== $id) {
            $em = $this->getDoctrine()->getManager();
            $show = $em->getRepository('AppBundle:Show')->find($id);
        } else {
            return $this->redirectToRoute('show_select');
        }
        $form = $this->createForm(ShowType::class, $show);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($show);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success($show->getShow() . ' updated!');

            return $this->redirectToRoute("homepage");
        }

        return $this->render('Show/showForm.html.twig',
                [
                    'form' => $form->createView(),
                    'show' => $show,
                    'action' => 'Edit show'
        ]);
    }
}
