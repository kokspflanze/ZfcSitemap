<?php

namespace ZfcSitemap;

use Zend\Router\Http;

return [
    'router' => [
        'routes' => [
            'ZfcSitemap' => [
                'type' => Http\Literal::class,
                'options' => [
                    'route'    => '/sitemap.xml',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\IndexFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\Sitemap::class => Service\SitemapFactory::class,
        ],
    ],
    'view_helpers' => [
        'aliases' => [
            'sitemapWidget' => View\Helper\Sitemap::class,
        ],
        'factories' => [
            View\Helper\Sitemap::class => View\Helper\SitemapFacotry::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'zfc-sitemap' => [
        'strategies' => [

        ],
    ],
];