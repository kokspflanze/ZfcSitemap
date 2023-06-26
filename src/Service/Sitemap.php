<?php

namespace ZfcSitemap\Service;

use Laminas\Cache\Storage\StorageInterface;
use Laminas\EventManager\EventManagerAwareInterface;
use Laminas\EventManager\EventManagerInterface;
use Laminas\Navigation\AbstractContainer;
use Laminas\View;

class Sitemap implements EventManagerAwareInterface
{
    const EVENT_SITEMAP = 'sitemap-container';

    /** @var EventManagerInterface */
    protected $events;

    /** @var View\Renderer\RendererInterface */
    protected $renderer;

    /** @var StorageInterface */
    protected $cache;

    /**
     * Sitemap constructor.
     * @param EventManagerInterface $events
     * @param View\Renderer\RendererInterface $renderer
     */
    public function __construct(EventManagerInterface $events, View\Renderer\RendererInterface $renderer, StorageInterface $storage)
    {
        $this->setEventManager($events);
        $this->renderer = $renderer;
        $this->cache = $storage;
    }

    /**
     * @param EventManagerInterface $events
     * @return $this
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers(array(
            __CLASS__,
            get_called_class(),
        ));
        $this->events = $events;
        return $this;
    }

    /**
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->events;
    }

    /**
     * @param string $url
     * @param string|null $containerString
     * @return string
     */
    public function getSitemap(string $url, ?string $containerString = null): string
    {
        $fileName = $this->getSitemapCacheKey($url);

        $sitemap = $this->cache->getItem($fileName);
        if ($sitemap !== null) {
            return $sitemap;
        }

        return $this->getNewSitemap($containerString);
    }

    /**
     * @param string $url
     * @param string|null $containerString
     */
    public function generateSitemapCache(string $url, ?string $containerString = null)
    {
        $siteMapString = $this->getNewSitemap($url, $containerString);
        $url = rtrim($url, '/');

        $siteMapString = str_replace(
            '>http://',
            sprintf(
                '>%s',
                str_replace('/sitemap.xml', '', $url)
            ),
            $siteMapString
        );

        $fileName = $this->getSitemapCacheKey($url);
        $success = $this->cache->setItem($fileName, $siteMapString);

        if (false === $success) {
            throw new \InvalidArgumentException('could not write sitemap into the cache');
        }
    }

    protected function getSitemapCacheKey(string $url) :string
    {
        $url = rtrim($url, '/');

        return 'zfc-sitemap-' . sha1($url);
    }

    /**
     * @param string|null $containerString
     * @return string
     */
    protected function getNewSitemap(string $url, ?string $containerString = null): string
    {
        /** @var View\Helper\Navigation $navigation */
        $navigation = $this->renderer->navigation($containerString);
        $container = $navigation->getContainer();

        $this->sitemapContainer($url, $container);

        return $navigation->sitemap()
            ->setFormatOutput(true)
            ->setRenderInvisible(true);
    }

    /**
     * @param AbstractContainer $container
     * @return \Laminas\EventManager\ResponseCollection
     */
    protected function sitemapContainer(string $url, AbstractContainer $container)
    {
        return $this->getEventManager()->trigger(
            self::EVENT_SITEMAP,
            $this,
            [
                'container' => $container,
                'url' => $url,
            ]
        );
    }
}
