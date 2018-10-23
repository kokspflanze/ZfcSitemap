<?php

namespace ZfcSitemap\View\Helper;

use ZfcSitemap\Service;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Navigation;

class Sitemap extends AbstractHelper
{
    /** @var Service\Sitemap */
    protected $sitemapService;

    /**
     * Sitemap constructor.
     * @param Service\Sitemap $sitemapService
     */
    public function __construct(Service\Sitemap $sitemapService)
    {
        $this->sitemapService = $sitemapService;
    }

    /**
     * @param null $container
     * @return Navigation
     */
    public function __invoke($container = null)
    {
        /** @var Navigation $navigation */
        $navigation = $this->getView()->navigation($container);
        $container = $navigation->getContainer();

        $this->sitemapService->sitemapContainer($container);

        return $navigation;
    }
}