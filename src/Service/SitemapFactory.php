<?php

namespace ZfcSitemap\Service;

use Interop\Container\ContainerInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class SitemapFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|Sitemap
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var EventManagerInterface $eventManager */
        $eventManager = $container->get('EventManager');
        
        $sitemap = new Sitemap($eventManager);
        foreach ($container->get('config')['zfc-sitemap']['strategies'] as $strategy) {
            $container->get($strategy)->attach($eventManager);
        }

        return $sitemap;
    }

}