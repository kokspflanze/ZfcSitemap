<?php

namespace ZfcSitemap\Controller;

use Laminas\Mvc\Console\Controller\AbstractConsoleController;
use Laminas\Router\RouteStackInterface;
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
        $this->router->setBaseUrl('');

        $event  = $this->getEvent();
        $event->setRouter($this->router);
        $router = $event->getRouter();
        $router->match(new \Laminas\Http\Request());
        $request = $this->getRequest();

        $this->siteMapService->generateSitemapCache($request->getParam('url', ''), 'default');
    }

}