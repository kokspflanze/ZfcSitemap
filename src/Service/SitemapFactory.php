<?php

namespace ZfcSitemap\Service;

use Interop\Container\ContainerInterface;
use Laminas\Cache\Service\StorageAdapterFactoryInterface;
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
        $cacheOptions = $config['cache'];
        /** @var StorageAdapterFactoryInterface $storageFactory */
        $storageFactory = $container->get(StorageAdapterFactoryInterface::class);

        if (empty($cacheOptions)) {
            $cacheOptions = [
                'adapter' => Filesystem::class,
                'options' => [
                    'cache_dir' => './data/cache',
                    'ttl' => 86400
                ],
                'plugins' => [
                    [
                        'name' => 'exception_handler',
                        'options' => [
                            'throw_exceptions' => false
                        ],
                    ],
                    [
                        'name' => 'serializer',
                    ],
                ],
            ];
        }
        
        $sitemap = new Sitemap(
            $eventManager,
            $container->get('ViewRenderer'),
            $storageFactory->create(
                $cacheOptions['adapter'],
                $cacheOptions['options'] ?? [],
                $cacheOptions['plugins'] ?? []
            )
        );

        foreach ($config['strategies'] as $strategy) {
            $container->get($strategy)->attach($eventManager);
        }

        return $sitemap;
    }

}