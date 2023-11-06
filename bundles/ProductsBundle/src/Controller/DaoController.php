<?php

namespace ProductsBundle\Controller;

use Pimcore\Controller\FrontendController;
use ProductsBundle\Model\Vote\Listing;

class DaoController extends FrontendController
{
    public function DaoAction()
    {
        $vote = new \ProductsBundle\Model\Vote();
        $vote->setUsername('jay');
        $vote->setScore(5);
//        $vote->save();

        return $this->render("@ProductsBundle/index.html.twig",['object'=>$vote]);

    }
    public function AdvacedAction()
    {
        $list = new Listing();
        $list->setCondition("score > ?", array(2));
        $votes = $list->load();
        return $this->render("@ProductsBundle/index.html.twig",['object'=>$votes]);



    }
}
