<?php

namespace ZfcSitemap\Service;

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

    /**
     * Sitemap constructor.
     * @param EventManagerInterface $events
     * @param View\Renderer\RendererInterface $renderer
     */
    public function __construct(EventManagerInterface $events, View\Renderer\RendererInterface $renderer)
    {
        $this->setEventManager($events);
        $this->renderer = $renderer;
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
        $fileName = $this->getSitemapFile($url);

        if (file_exists($fileName) === true) {
            return file_get_contents($fileName);
        }

        return $this->getNewSitemap($containerString);
    }

    /**
     * @param string $url
     * @param string|null $containerString
     */
    public function generateSitemapCache(string $url, ?string $containerString = null)
    {
        $siteMapString = $this->getNewSitemap($containerString);
        $url = rtrim($url, '/');

        $siteMapString = str_replace(
            '>http://',
            sprintf(
                '>%s',
                $url
            ),
            $siteMapString
        );

        if (!is_dir('./data/zfc-sitemap')) {
            throw new \InvalidArgumentException('"./data/zfc-sitemap" is missing');
        }
        $fileName = $this->getSitemapFile($url);
        $success = file_put_contents($fileName, $siteMapString);

        if (false === $success) {
            throw new \InvalidArgumentException(
                sprintf(
                    'could not write sitemap in "%s", check user write rights',
                    $fileName
                )
            );
        }
    }

    protected function getSitemapFile(string $url) :string
    {
        $url = rtrim($url, '/');

        return sprintf('./data/zfc-sitemap/sitemap-%s.xml', sha1($url));
    }

    /**
     * @param string|null $containerString
     * @return string
     */
    protected function getNewSitemap(?string $containerString = null): string
    {
        /** @var View\Helper\Navigation $navigation */
        $navigation = $this->renderer->navigation($containerString);
        $container = $navigation->getContainer();

        $this->sitemapContainer($container);

        return $navigation->sitemap()
            ->setFormatOutput(true)
            ->setRenderInvisible(true);
    }

    /**
     * @param AbstractContainer $container
     * @return \Laminas\EventManager\ResponseCollection
     */
    protected function sitemapContainer(AbstractContainer $container)
    {
        return $this->getEventManager()->trigger(self::EVENT_SITEMAP, $this, ['container' => $container]);
    }
}