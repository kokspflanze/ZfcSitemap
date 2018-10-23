<?php

namespace ZfcSitemap\Service;

use Zend\EventManager\EventManagerAwareInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Navigation\AbstractContainer;

class Sitemap implements EventManagerAwareInterface
{
    const EVENT_SITEMAP = 'sitemap-container';

    /** @var EventManagerInterface */
    protected $events;

    /**
     * Sitemap constructor.
     *
     * @param EventManagerInterface $events
     */
    public function __construct(EventManagerInterface $events)
    {
        $this->setEventManager($events);
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
     * @param AbstractContainer $container
     * @return \Zend\EventManager\ResponseCollection
     */
    public function sitemapContainer(AbstractContainer $container)
    {
        return $this->getEventManager()->trigger(self::EVENT_SITEMAP, $this, ['container' => $container]);
    }
}