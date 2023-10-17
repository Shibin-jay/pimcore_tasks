<?php

namespace App\Controller;

use Pimcore\Bundle\ApplicationLoggerBundle\FileObject;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject;
use Pimcore\Model;
use Pimcore\SystemSettingsConfig;
use Symfony\Component\HttpFoundation\Request;
use App\Model\Product\AdminStyle\Car;
use Pimcore\Bundle\ApplicationLoggerBundle\ApplicationLogger;
use App\Service\CustomLogService;
class ToolsController extends FrontendController
{
    public function versionInfoAction(Request $request,ApplicationLogger $logger )
    {
        $object = Model\DataObject::getById(5);

        $note = new Model\Element\Note();
        $note->setElement($object);
        $note->setDate(time());
        $note->setType("erp_import");
        $note->setTitle("changed availabilities to xyz");
        $note->setUser(0);

        $note->addData("myText", "text", "Some Text");
        $note->addData("myObject", "object", Model\DataObject::getById(7));
        $note->addData("myDocument", "document", Model\Document::getById(18));
        $note->addData("myAsset", "asset", Model\Asset::getById(20));

        $note->save();

        $currentObject= DataObject::getById(6);
        $versions = $currentObject->getVersions();
        $previousVersion = $versions[count($versions)-2];
        $previousObject = $previousVersion->getData();

        $logger->error('Your error message');
        $logger->alert( 'Your alert');
        $logger->debug('Your debug message', ['foo' => 'bar']);

        return $this->render('Tools/default.html.twig',[
           'items'=>$versions,
            'recent'=>$previousObject

        ]);
    }
    public function testAction(ApplicationLogger $logger)
    {
        $myObject   = DataObject::getById(54);
        $fileObject = new FileObject((string)$myObject);


        $logger->error('my error message', [
            'fileObject'    => $fileObject,
            'relatedObject' => $myObject,
            'component'     => 'different component',
            'source'        => 'Stack trace or context-relevant information'
        ]);
        return $this->render('Tools/default.html.twig',[
            'items'=>$fileObject
        ]);
    }
    public function systemAction(Request $request,SystemSettingsConfig $config, CustomLogService $customLogService)
    {
        $config = $config->getSystemSettingsConfig();
        $bar = $config['general']['valid_languages'];
        $customLogService->loggerService();

        return $this->render('Tools/default.html.twig',['items'=>$bar,'recent'=>$config]);

    }
    public function websiteAction(Request $request,SystemSettingsConfig $config)
    {
        $setting = \Pimcore\Model\WebsiteSetting::getByName('customObj', null, 'en');
        $currentnumber = $setting->getData();


        return $this->render('Tools/default.html.twig',['items'=>$setting,'recent'=>$currentnumber]);

    }

}
