<?php

namespace ZfcSitemap\View\Helper;

use ZfcSitemap\Service;
use Laminas\View\Helper\AbstractHelper;

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
     * @param null|string $container
     * @return string
     */
    public function __invoke(?string $container = null): string
    {
        return $this->sitemapService->getSitemap($container);
    }
}