<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Controller\BlockController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Block;
use AppBundle\Form\BlockType;
use AppBundle\Services\Defaults;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * BlockController
 * @Route("/block")
 */
class BlockController extends Controller
{

    /**
     * @Route("/new", name="new_block")
     * @param Request $request
     * @param Defaults $defaults
     */
    public function addBlockAction(Request $request, Defaults $defaults)
    {
        $block = new Block();
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($block);
            $em->flush();
            $flash = $this->get('braincrafted_bootstrap.flash');
            $flash->success('Block added!');
        }

        return $this->render('Block/newBlock.html.twig', [
                'form' => $form->createView(),
        ]);
    }
}
