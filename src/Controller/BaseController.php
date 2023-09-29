<?php

namespace  App\Controller;


use Pimcore\Controller\FrontendController;
use Pimcore\Model\Asset;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends FrontendController
{
    public function  defautlAction(){
        return $this->render('base/default.html.twig', [
            'isPortal' => true
        ]);
    }
    #[Template('base/my_gallery.html.twig')]
    public function myGalleryAction(Request $request): array
    {
        if ('asset' === $request->get('type')) {
            $asset = Asset::getById((int) $request->get('id'));
            if ('folder' === $asset->getType()) {
                return [
                    'assets' => $asset->getChildren()
                ];
            }
        }
        return [];
    }
    #[Template('base/test-editables.html.twig')]
    public function testEditablesAction(){
        return [];
    }
    public function footerAction(){
        return $this->render('base/footer.html.twig');
    }
    public function customAction(){
        return $this->render('base/custom.html.twig');

    }



}
