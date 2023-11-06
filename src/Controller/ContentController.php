<?php
namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject;
use Pimcore\Model\Document\Link;
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

    public function customProductsAction(Request $request):Response
    {
        $locale = $request->getLocale();
        $link = Link::getById(15)->getHref();
        return  $this->render('content/custom_products.html.twig',['link'=>$link, 'locale'=>$locale]);
    }

//    public function passingObjectAction(){
//        $product = DataObject::getById(3);
//        return $this->render('content/passing_object.html.twig',['product'=>$product]);
//    }
}
