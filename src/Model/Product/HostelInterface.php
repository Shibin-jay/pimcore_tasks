<?php

namespace App\Model\Product;

interface HostelInterface
{
    public function getPeopleCount(): ?int;

    public function getRoomType(): ?string;

}
