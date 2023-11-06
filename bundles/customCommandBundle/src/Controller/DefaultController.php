<?php

namespace customCommandBundle\Controller;

use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends FrontendController
{
    /**
     * @Route("/custom_command")
     */
    public function indexAction(Request $request): Response
    {
        return new Response('Hello world from custom_command');
    }
}