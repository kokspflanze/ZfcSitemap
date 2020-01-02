<?php

namespace ZfcSitemap\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use ZfcSitemap\Service;

class GenerateFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|GenerateController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        \Laminas\Navigation\Page\Mvc::setDefaultRouter($container->get('HttpRouter'));
        return new GenerateController($container->get(Service\Sitemap::class), $container->get('HttpRouter'));
    }

}