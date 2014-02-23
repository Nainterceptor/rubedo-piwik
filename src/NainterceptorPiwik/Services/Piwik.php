<?php

namespace NainterceptorPiwik\Services;

use NainterceptorPiwik\Libraries\PiwikTracker;
use Rubedo\Exceptions\Server;
use Rubedo\Services\Manager;
use Zend\Mvc\MvcEvent;

class Piwik
{
    /**
     * Config for module
     *
     * @var array
     */
    static $config = array();
    static function track(MvcEvent $event)
    {
        $controller = $event->getRouteMatch()->getParam('controller');
        $isBackoffice = strpos($controller, 'Rubedo\\Backoffice\\Controller') === 0;

        //Do not track if it's backoffice
        if ($isBackoffice) {
            return;
        }

        /**
         * Services
         *
         * @var \Rubedo\Interfaces\Content\IPage $pageContentService
         * @var \Rubedo\Interfaces\Collection\ISites $sitesService
         */
        $pageContentService = Manager::getService('PageContent');
        $sitesService = Manager::getService('Sites');

        $site = array('title' => null);

        if ($pageContentService->getCurrentSite()) {
            $siteId = $pageContentService->getCurrentSite();
            $site = $sitesService->findById($siteId);
        }

        $title = $pageContentService->getPageTitle();

        if (!empty($site['title'])) {
            $title = $site['title'] . ' - ' . $title;
        }
        $config = static::getConfig();
        if (
            isset($site['text'])
            && !empty($config['trackerURL'])
            && !empty($config['sitesID'][$site['text']])
        ) {
            (new PiwikTracker(1, $config['trackerURL']))
                ->doTrackPageView($title);
        }
        // Else, just do not track
    }

    /**
     * Get config from module.php file.
     * @todo override site interface, when it's possible.
     *
     * @return array
     * @throws \Rubedo\Exceptions\Server
     */
    static function getConfig() {
        if (empty(static::$config)) {
            $path = realpath(__DIR__ . '/../../../config/module.php');
            if (!file_exists($path)) {
                throw new Server('Config file for rubedo-piwik module not exist, please read README.md');
            }
            $config = include $path;
            if (empty($config))
                throw new Server('Config for rubedo-piwik is empty, please read README.md');
            self::$config = $config;
        }
        return static::$config;
    }
}