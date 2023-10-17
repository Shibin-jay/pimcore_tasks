<?php

namespace App\Model\Product\AdminStyle;

use Pimcore\Model\DataObject;
use Pimcore\Model\Element\AdminStyle;
use Pimcore\Model\Element\ElementInterface;

class Car extends AdminStyle
{
    protected ElementInterface $element;

    public function __construct(ElementInterface $element)
    {
        parent::__construct($element);

        $this->element = $element;

        if ($element instanceof DataObject\Customer) {
            DataObject\Service::useInheritedValues(true, function () use ($element) {
                if ($element->getId() == 8) {
                    $this->elementIcon = '/bundles/pimcoreadmin/img/flat-color-icons/news.svg';
                }
            });
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getElementQtipConfig(): ?array
    {
        if ($this->element instanceof DataObject\Customer) {
            $element = $this->element;

            return DataObject\Service::useInheritedValues(true, function () use ($element) {
                $text = '<h4>' . $element->getName() . '</h4>';

                $mainImage = $element->getImage();
                if ($mainImage) {
                    $thumbnail = $mainImage->getThumbnail("test");
                    $text .= '<p><img src="' . $thumbnail . '" width="150" height="150"/></p>';
                }

//                $collection = DataObject\Fieldcollection\Data\AddressCollection::FIELD_ADRESS;
//                $firstBlock = $element->getBlock();
//                $collection = =$firstBlock
//                $text .= wordwrap($collection, 50, "<br>");

                return [
                    "title" => "ID: " . $element->getId() . " - Year: " . $element->getCreationDate(),
                    "text" => $text,
                ];
            });
        }

        return parent::getElementQtipConfig();
    }
}
