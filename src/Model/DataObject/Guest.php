<?php

namespace App\Model\DataObject;

class Guest extends \Pimcore\Model\DataObject\Guest
{
    protected ?string $customVAlue;
    public function getAddress(): ?string
    {
        return $this->customVAlue;
    }
    public function setAddress(?string $address): static
    {
        $this->customVAlue= $address;
        return $this;
    }
}
