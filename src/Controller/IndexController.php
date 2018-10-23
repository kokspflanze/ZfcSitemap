<?php

namespace ZfcSitemap\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $this->getResponse()->getHeaders()->addHeaders(['Content-type' => 'application/xml']);
    }
}