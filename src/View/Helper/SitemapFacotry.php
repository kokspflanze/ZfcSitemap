<?php

namespace ZfcSitemap\View\Helper;

use ZfcSitemap\Service;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class SitemapFacotry implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        \Laminas\Navigation\Page\Mvc::setDefaultRouter($container->get('router'));
        return new Sitemap($container->get(Service\Sitemap::class));
    }

}