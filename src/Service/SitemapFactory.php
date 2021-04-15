<?php

namespace ZfcSitemap\Service;

use Interop\Container\ContainerInterface;
use Laminas\Cache\Storage\Adapter\Filesystem;
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
        $cacheConfig = $config['cache'];

        if (empty($cacheConfig)) {
            $cacheConfig = [
                'adapter' => Filesystem::class,
                'options' => [
                    'cache_dir' => './data/cache',
                    'ttl' => 86400
                ],
                'plugins' => [
                    'exception_handler' => [
                        'throw_exceptions' => false
                    ],
                    'serializer',
                ],
            ];
        }
        
        $sitemap = new Sitemap(
            $eventManager,
            $container->get('ViewRenderer'),
            StorageFactory::factory($cacheConfig)
        );

        foreach ($config['strategies'] as $strategy) {
            $container->get($strategy)->attach($eventManager);
        }

        return $sitemap;
    }

}