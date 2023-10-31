<?php

namespace ProductsBundle\Controller;

use Pimcore\Model\DataObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/products")
     */
    public function indexAction(Request $request): Response
    {
        return new Response('Hello world from products');
    }

    /**
     * @Route("/products/id={id}", name= "product_preview")
     */
    public function preview(Request $request,$id)
    {
        $object = DataObject::getById($id);
//        var_dump($id);
//        $requestData = json_decode($request->getContent(), true);
//        $objectId = $requestData['objectId'];
        return $this->render("@ProductsBundle/index.html.twig",['object'=>$object]);
//        return new Response(var_dump($object));
    }

}
