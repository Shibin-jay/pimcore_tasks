<?php
use Pimcore\Bundle\SimpleBackendSearchBundle\PimcoreSimpleBackendSearchBundle;
use Pimcore\Bundle\ApplicationLoggerBundle\PimcoreApplicationLoggerBundle;
use Pimcore\Bundle\CustomReportsBundle\PimcoreCustomReportsBundle;
use Pimcore\Bundle\BundleGeneratorBundle\PimcoreBundleGeneratorBundle;

return [
    PimcoreSimpleBackendSearchBundle::class => ['all' => true],
    PimcoreApplicationLoggerBundle::class => ['all' => true],
    PimcoreCustomReportsBundle::class=>['all'=>true],
    PimcoreBundleGeneratorBundle::class => ['all' => true],
    TestBundle\TestBundle::class => ['all' => true],
    ProductsBundle\ProductsBundle::class=>['all' => true],
    customLogBundle\customLogBundle::class => ['all' => true],
    customConfigBundle\customConfigBundle::class => ['all' => true],
    customCommandBundle\customCommandBundle::class => ['all' => true],
    //Twig\Extra\TwigExtraBundle\TwigExtraBundle::class => ['all' => true],
];
