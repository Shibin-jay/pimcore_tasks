<?php

namespace App\EventListener;

use Pimcore\Model\DataObject\Customer;

class AdminStyleListener
{
    public function onResolveElementAdminStyle(\Pimcore\Bundle\AdminBundle\Event\ElementAdminStyleEvent $event): void
    {
        $element = $event->getElement();
        // decide which default styles you want to override
        if ($element instanceof Customer) {
            $event->setAdminStyle(new \App\Model\Product\AdminStyle\Car($element));
        }
    }
}
