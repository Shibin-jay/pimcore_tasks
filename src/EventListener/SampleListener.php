<?php

namespace App\EventListener;

use Pimcore\Event\Model\ElementEventInterface;
use Pimcore\Event\Model\AssetEvent;
use Pimcore\Event\Model\DocumentEvent;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Bundle\ApplicationLoggerBundle\ApplicationLogger;
class SampleListener
{
    private $logger;
    public function __construct(ApplicationLogger $logger)
    {
        $this->logger= $logger;
    }

    public function onPreupdate(ElementEventInterface $event)
    {
        if($event instanceof AssetEvent){
            $asset  = $event->getAsset();
            if($asset->getFileSize()> (2*1024*1024)){
                $this->logger->debug("Asset greater than 2mb is updated");
            }
            else{
                $this->logger->debug("Asset was updated");
            }
        }
        elseif ($event instanceof DataObjectEvent ){
            $this->logger->debug("Object was updated");
        }
        elseif ($event instanceof DocumentEvent){
            $this->logger->debug(" Document was updated");
        }
    }
    public function onPreDelete(ElementEventInterface $event){
        if($event instanceof AssetEvent){
            $this->logger->debug("Asset was deleted");
        }
        elseif ($event instanceof DataObjectEvent ){
            $this->logger->debug("Object was deleted");
        }
        elseif ($event instanceof DocumentEvent){
            $this->logger->debug(" Document was deleted");
        }
    }
    public function onPreAdd(ElementEventInterface $event){
        if($event instanceof AssetEvent){
            $this->logger->debug("Asset was Added");
        }
        elseif ($event instanceof DataObjectEvent ){
            $this->logger->debug("Object was Added");
        }
        elseif ($event instanceof DocumentEvent){
            $this->logger->debug(" Document was Added");
        }
    }
}
