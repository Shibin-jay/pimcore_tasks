<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Pimcore\Controller\FrontendController;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\Data\BlockElement;
use Pimcore\Model\Document;
use Pimcore\Model\DataObject\TestRoom;

class ObjectController extends  FrontendController
{
    public function BlockAction(){
        $object = DataObject\Room::getById(7);
        $firstBlock = $object->getBlock();
        $secondBlock = $object->getCustom();
        $item = $secondBlock[0];
        $item2 =$item["date"]->setData(time());
        $createdBlock = $this->CreateBlockAction();

        return $this->render('Object/block.html.twig',['firstBlocks'=>$firstBlock, 'secondBlock'=>$secondBlock,'createdBlock'=>$createdBlock]);
    }


    public function CreateBlockAction(){
        $object = DataObject::getById(2);
        $data=[
            'name'=> new BlockElement('name','input','301 C'),
            'description'=> new BlockElement('description','input','description new')
        ];
        $blockElement = new DataObject\Room();
        return $blockElement->setBlock([$data]);

//        return $blockElement;
    }
    public function fieldCollectionAction(){
        $object = DataObject\Customer::getById(8);
        $collection = DataObject\Fieldcollection\Data\AddressCollection::FIELD_HOUSE_NAME;
        return $this->render('Object/default.html.twig',['items'=>$collection]);
    }
    public function  CreateFieldAction(){
        $object = new DataObject\Customer();
        $object->setParentId(1);
        $object->setUserOwner(1);
        $object->setUserModification(1);
        $object->setCreationDate(time());
        $object->setKey(uniqid() . rand(10, 99));
        $items = new DataObject\Fieldcollection();

        for ($i = 0; $i < 5; $i++) {
            $item = new DataObject\Fieldcollection\Data\DemoCollection();
            $item->setname("This is a test " . $i);
            $item->setGender('male');
            $item->setIsDisabled(0);
            $items->add($item);
        }
        $object->save();
        return $this->render('Object/field.html.twig',['items'=>$items]);

    }
    public function galleryAction(){
        $galleryData = [
            'images/perfu2.jpg',
            'images/perfu3.jpg',
            'images/perfu4.jpg',
            'images/perfu5.jpg',
            'images/perfu6.jpg'
        ];
        $items = [];
        $ourgallery = new DataObject\Gallery();
        $ourgallery->setKey('Formgallery-1');
        $ourgallery->setParent(DataObject\AbstractObject::getById('3'));
        $ourgallery->setPublished(true);



//        foreach($galleryData as $img){
//            $advancedImage = new \Pimcore\Model\DataObject\Data\Hotspotimage();
//            $advancedImage->setImage($img);
//            $items[] = $advancedImage;
//        }
        foreach($galleryData as $imgPath){
            $image = new \Pimcore\Model\Asset\Image();
            $image->setFilename(basename($imgPath));
            $image->setData(file_get_contents($imgPath));

            $hotspotImage = new \Pimcore\Model\DataObject\Data\Hotspotimage();
            $hotspotImage->setImage($image);

            $items[] = $hotspotImage;
        }
//        $ourgallery->setGallery(new \Pimcore\Model\DataObject\Data\ImageGallery($items));

        return $this->render('Object\gallery.html.twig',['gallery'=>$items]);

    }
    public function localDataAction(){
        $object = DataObject::getById(48);
        $object->getName();
        $object->setName("Custom Name", "fr");
        return $this->render('Object\local-data.html.twig',['object'=>$object]);
    }
    public function manyAction(){
        $object = DataObject::getById(49);
//        $object->setMyManyToManyObjectField([
//            DataObject\Product::getById(3),
//            DataObject\Product::getById(49)
//        ]);
//        $object->setMyManyToOneField(Document::getById(48));
//        $object->setMyManyToManyField([
//            Asset::getById(4),
//            DataObject::getByPath("/")
//        ]);
        $object->save();

        return $this->render('Object\gallery.html.twig',['gallery'=>$object]);
    }
    public function videoAction(){
        $obj = DataObject::getById(52);
        $video = Asset::getById(3);
        $image = Asset::getById(4);

        $videoData =new DataObject\Data\Video();
        $videoData->setData($video);
        $videoData->setType("asset");
        $videoData->setPoster($image);
        $videoData->setTitle("Custom Title");
        $videoData->setDescription("Custom Description");

        $obj->setVideo($videoData);
        $obj->save();

        return $this->render('Object\video.html.twig',['items'=>$obj]);
    }
    public function bricksAction(){
        $prod = DataObject::getById(53);
        $prod->getObjBrick()->getBikes()->setengineCapacity(1001);
        $prod->save();

        return $this->render('Object\bricks.html.twig',['item'=>$prod]);
    }
    public function classificationStoreAction()
    {
        $dataObject = TestRoom::getById(54);
        $classificationStore = $dataObject->getStore();

        foreach ($classificationStore->getGroups() as $group) {
            $groupData = [
                'groupName' => $group->getConfiguration()->getName(),
                'keys' => []
            ];

            foreach ($group->getKeys() as $key) {
                $keyConfiguration = $key->getConfiguration();

                $value = $key->getValue();
                if ($value instanceof \Pimcore\Model\DataObject\Data\QuantityValue) {
                    $value = (string)$value;
                }

                $groupData['keys'][] = [
                    'id' => $keyConfiguration->getId(),
                    'name' => $keyConfiguration->getName(),
                    'value' => $value,
                    'isQuantityValue' => ($key->getFieldDefinition() instanceof QuantityValue),
                ];
            }
            $classificationStoreData[] = $groupData;

        }
        return $this->render('Object\store.html.twig', ['items' => $classificationStoreData]);
    }

    public function CreateVariantAction()
    {
        $obj = new DataObject\Guest();
        $obj->setParent(DataObject\Guest::getById(92));
        $obj->setKey("testvariant26");
        $obj->setName("test name");
        $obj->setAddress('testaddress');
        $obj->setAge(23);
        $obj->setEmail('test@gmail.com');
        $obj->setType(DataObject::OBJECT_TYPE_VARIANT);
//        $obj->save();

        return $this->render('Object/default.html.twig',['items'=>$obj]);
    }
    public function lockAction(Request $request){
        $class = DataObject\ClassDefinition::getById(15);
        $fields = $class->getFieldDefinitions();

        foreach ($fields as $field) {
            $field->setLocked(false);
        }

        $class->save();
        return $this->render('Object/default.html.twig',['items'=>$class]);
    }

    /*
//     *@Route("{path}
//     */
//    public function previewAction(){
//
//    }


}
