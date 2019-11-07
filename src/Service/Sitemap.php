<?php

namespace ZfcSitemap\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Navigation\AbstractContainer;
use Zend\View;

class Sitemap implements EventManagerAwareInterface
{
    const EVENT_SITEMAP = 'sitemap-container';

    /** @var EventManagerInterface */
    protected $events;

    /** @var View\View */
    protected $view;

    /**
     * Sitemap constructor.
     * @param EventManagerInterface $events
     * @param View\View $view
     */
    public function __construct(EventManagerInterface $events, View\View $view)
    {
        $this->setEventManager($events);
        $this->view = $view;
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
     * @param string|null $containerString
     * @return string
     */
    public function getSitemap(?string $containerString = null): string
    {
        /** @var View\Helper\Navigation $navigation */
        $navigation = $this->view->navigation($containerString);
        $container = $navigation->getContainer();

        $this->sitemapContainer($container);

        return $navigation->sitemap()
            ->setFormatOutput(true)
            ->setRenderInvisible(true);
    }

    /**
     * @param AbstractContainer $container
     * @return \Zend\EventManager\ResponseCollection
     */
    public function sitemapContainer(AbstractContainer $container)
    {
        return $this->getEventManager()->trigger(self::EVENT_SITEMAP, $this, ['container' => $container]);
    }
}