<?php

namespace ZfcSitemap\Controller;

use Zend\Mvc\Console\Controller\AbstractConsoleController;
use ZfcSitemap\Service\Sitemap;

class GenerateController extends AbstractConsoleController
{
    /** @var Sitemap */
    protected $siteMapService;

    /**
     * GenerateController constructor.
     * @param Sitemap $siteMapService
     */
    public function __construct(Sitemap $siteMapService)
    {
        $this->siteMapService = $siteMapService;
    }

    public function indexAction()
    {
        echo $this->siteMapService->getSitemap('default');
    }

}