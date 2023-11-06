<?php


namespace App\Controller;

use Pimcore\Model\DataObject\ClassDefinition\Data;
use Pimcore\Model\DataObject\ClassDefinition\DynamicOptionsProvider\SelectOptionsProviderInterface;


class OptionsProvider implements SelectOptionsProviderInterface
{
    public function getOptions(array $context, Data $fieldDefinition): array
    {
        $object = isset($context["object"]) ? $context["object"] : null;
        $fieldname = "id: " . ($object ? $object->getId() : "unknown") . " - " . $context["fieldname"];
        $result = array(

            array("key" => $fieldname . ' == A', "value" => 2),
            array("key" => $fieldname . ' == C', "value" => 4),
            array("key" => $fieldname . ' == F', "value" => 5)

        );
        return $result;
    }

    /**
     * Returns the value which is defined in the 'Default value' field
     */
    public function getDefaultValue(array $context, Data $fieldDefinition): ?string
    {
        return $fieldDefinition->getDefaultValue();
    }

    public function hasStaticOptions(array $context, Data $fieldDefinition): bool
    {
        return true;
    }

}
