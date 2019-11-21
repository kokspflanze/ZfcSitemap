<?php

namespace ZfcSitemap\Controller;

use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Zend\Router\RouteStackInterface;
use Zend\Uri\Http as HttpUri;
use ZfcSitemap\Service\Sitemap;

class GenerateController extends AbstractConsoleController
{
    /** @var Sitemap */
    protected $siteMapService;

    /** @var RouteStackInterface */
    protected $router;

    /**
     * GenerateController constructor.
     * @param Sitemap $siteMapService
     * @param RouteStackInterface $router
     */
    public function __construct(Sitemap $siteMapService, RouteStackInterface $router)
    {
        $this->siteMapService = $siteMapService;
        $this->router = $router;
    }

    public function indexAction()
    {
        $requestUri = new HttpUri();
        $requestUri->setHost('example.com')
            ->setScheme('https');
        $this->router->setRequestUri($requestUri);
        $this->router->setBaseUrl('foobar');

        $event  = $this->getEvent();
        $event->setRouter($this->router);
        $router = $event->getRouter();
        $router->match(new \Zend\Http\Request());

        echo $this->siteMapService->getSitemap('default');
    }

}