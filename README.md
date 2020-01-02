# ZfcSitemap Module for Laminas

## SYSTEM REQUIREMENTS

- requires PHP 7.1 or later; we recommend using the latest PHP version whenever possible.
- you have to use `laminas-navigation`

## INSTALLATION

### Composer

Installation of this module uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
php composer.phar require kokspflanze/zfc-sitemap
# (When asked for a version, type `dev-master`)
```

Go to `config/application.config.php` and add `ZfcSitemap` in the modules section.

## How to use

### Get the sitemap

you can see your current sitemap with `/sitemap.xml`

### Create a sitemap as cache (optional)

This create a sitemap in `data/zfc-sitemap` as cache, this means, if you call `/sitemap.xml` it will not longer create a new sitemap, it will use the cached sitemap.

For this you need to install `laminas/laminas-mvc-console` and this directory `data/zfc-sitemap` with write rights.

Than you can execute  `php public/index.php generate-sitemap http://example.com` to create your sitemap.
PS: You have to rerun it to create a new sitemap.

### Change your sitemap

If you have dynamic pages on your page and you want to add them in the sitemap. You can add them with the EventManager.

#### Create a listener

create a new class

````php
<?php

namespace App\Core\Listener;

use Laminas\EventManager;
use Laminas\Navigation\AbstractContainer;
use ZfcSitemap\Service\Sitemap;

class SitemapCustom extends EventManager\AbstractListenerAggregate
{
    /**
     * @inheritDoc
     */
    public function attach(EventManager\EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(Sitemap::EVENT_SITEMAP, [$this, 'onSiteMap'], $priority);
    }

    /**
     * @param EventManager\EventInterface $event
     */
    public function onSiteMap(EventManager\EventInterface $event)
    {
        /** @var AbstractContainer $container */
        $container = $event->getParam('container');

        $container->addPage([
            'label' => 'Example',
            'uri' => '/example',
        ]);
        /**
        *  your custom stuff, add or remove pages
         */

        $event->setParam('container', $container);
    }

}
````

Than you have to add your listener in the `service_manager` config and in the following configuration.

````php
    'zfc-sitemap' => [
        'strategies' => [
            Listener\SitemapCustom::class,
        ],
    ],
````
