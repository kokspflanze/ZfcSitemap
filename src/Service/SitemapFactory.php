<?php

namespace ZfcSitemap\Service;

use Interop\Container\ContainerInterface;
use Laminas\Cache\StorageFactory;
use Laminas\EventManager\EventManagerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

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

        $config = $container->get('config')['zfc-sitemap'];
        
        $sitemap = new Sitemap(
            $eventManager,
            $container->get('ViewRenderer'),
            StorageFactory::factory($config['cache'])
        );

        foreach ($config['strategies'] as $strategy) {
            $container->get($strategy)->attach($eventManager);
        }

        return $sitemap;
    }

}