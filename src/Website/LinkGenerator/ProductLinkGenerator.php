<?php
namespace  App\Website\LinkGenerator;

use Pimcore\Model\DataObject\ClassDefinition\LinkGeneratorInterface;
use Pimcore\Model\DataObject\Product;

class ProductLinkGenerator implements LinkGeneratorInterface
{
    public function generate(object $object, array $params = []): string
    {
        if(!($object instanceof Product )){
            throw new \InvalidArgumentException('Invalid object type.');
        }
        $sku = $object->getSku();
        $name = $object->getName();

//        $slug = $this->doGenerate($sku, $name);

        return ('/products/'.$sku);
    }
    public function doGenerate($sku, $name)
    {
        $urlFriendlyString = strtolower(str_replace(' ', '-', $name));
        return strtolower( $sku . '/'. $urlFriendlyString);
    }
}

