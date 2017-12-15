<?php

namespace AppBundle\Controller;

use AppBundle\Services\Defaults;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/defaultShow", name="default_show")
     */
    public function defaultShowAction(Defaults $defaults)
    {
        $show = $defaults->showDefault();
        $name = (empty($show) || is_null($show)) ? 'Active show not set' : $show->getShow();

        $response = new Response($name);

        return $response;
    }
}
