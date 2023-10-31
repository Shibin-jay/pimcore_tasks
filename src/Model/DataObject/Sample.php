<?php

class Sample extends \Pimcore\Model\DataObject\Listing\Concrete
{
    protected ?string $customValue;
    public function getAddress(): ?string
    {
        return $this->customValue;
    }
    public function setAddress(?string $address): static
    {
        $this->customValue= $address;
        return $this;
    }
}
