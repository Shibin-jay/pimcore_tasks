<?php

namespace App\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\Asset;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/asset', name: 'asset')]
class AssetController extends FrontendController
{
    #[Route('/create', name: 'create_image')]
    public function createImageAction()
    {
        $newAsset = new Asset();
        $newAsset->setFilename("myAsset2.png");
        $newAsset->setData(file_get_contents("images/shoes1.jpg"));
        $newAsset->setParent(\Pimcore\Model\Asset::getByPath("/mine"));
        $newAsset->addMetadata("title", "input", "the new title", "en");
        $newAsset->save(["versionNote" => "my new version"]);

        return $this->render('Asset\default.html.twig', ['asset' => $newAsset]);
    }

    #[Route('/get/{id}', name: 'get_image')]
    public function getAssetAction($id)
    {
        $asset = Asset::getById($id);

        if (!$asset) {
            throw $this->createNotFoundException('Asset not found');
        }

        return $this->render('Asset\default.html.twig', ['asset' => $asset]);
    }

    #[Route('/delete/{id}', name: 'delete_image')]
    public function deleteAssetAction($id)
    {
        $asset = Asset::getById($id);

        if (!$asset) {
            throw $this->createNotFoundException('Asset not found');
        }

        // Delete the asset
        $asset->delete();
//        var_dump($asset);

        return $this->redirect('/');

    }

}
