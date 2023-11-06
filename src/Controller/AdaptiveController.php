<?php

namespace  App\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\Model\DataObject;
use Pimcore\Model\User;
use Pimcore\Tool\DeviceDetector;
use Symfony\Component\HttpFoundation\Response;

class AdaptiveController extends FrontendController
{
    public function testAction(): Response
    {
        $device = DeviceDetector::getInstance();
        $device->getDevice();
        return $this->render('test\test.html.twig',['item'=>$device]);
    }
    public function extendingAction()
    {

        $user = User::create(
            [
                "parentId" => (0),
                "username" => "jackie",
                "password" => "password1234",
                "hasCredentials" => true,
                "active" => true

            ]
        );

        $object= new DataObject\Member();
        $object->setUser($user->getId());

//        $object->setCreationDate(time());
//        $object->setUserOwner($currentUser->getId());
//        $object->setUserModification($currentUser->getId());
//        $object->setPublished(true);
        return $this->render('test\test.html.twig',['item'=>$object]);

    }
    public function overAction()
    {
        $obj = DataObject\Guest::getById(86);
        $obj->setAddress('custom Address');

        return $this->render('test\test.html.twig',['item'=>$obj->getAddress()]);
    }
    public function parentAction()
    {
        $obj = DataObject\Concrete::getById(100);
//        $obj->;

        return $this->render('test\test.html.twig',['item'=>$obj]);
    }
}
