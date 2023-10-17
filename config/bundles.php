<?php
use Pimcore\Bundle\SimpleBackendSearchBundle\PimcoreSimpleBackendSearchBundle;
use Pimcore\Bundle\ApplicationLoggerBundle\PimcoreApplicationLoggerBundle;
use Pimcore\Bundle\CustomReportsBundle\PimcoreCustomReportsBundle;
return [
    PimcoreSimpleBackendSearchBundle::class => ['all' => true],
    PimcoreApplicationLoggerBundle::class => ['all' => true],
    PimcoreCustomReportsBundle::class=>['all'=>true],
    //Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
];
