<?php

namespace ZfcSitemap\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $view = new ViewModel();

        // Disable layouts; `MvcEvent` will use this View Model instead
        $view->setTerminal(true);
        $this->getResponse()->getHeaders()->addHeaders(['Content-type' => 'application/xml']);

        return $view;
    }
}