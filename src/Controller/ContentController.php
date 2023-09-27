<?php
namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContentController extends FrontendController
{
    #[Template('content/default.html.twig')]
    public function defaultAction(Request $request): array
    {
        return [];
    }

    public function customProductsAction():Response
    {
        return  $this->render('content/custom_products.html.twig');
    }

//    public function passingObjectAction(){
//        $product = DataObject::getById(3);
//        return $this->render('content/passing_object.html.twig',['product'=>$product]);
//    }
}
