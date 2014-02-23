<?php
namespace NainterceptorPiwik;

use Rubedo\Services\Events;
use Rubedo\Services\Manager;
use Zend\EventManager\EventManager;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $this->setListeners($eventManager);

    }
    public function setListeners(EventManager $eventManager)
    {
        $eventManager->attach(MvcEvent::EVENT_FINISH, array(
            'NainterceptorPiwik\Services\Piwik',
            'track'
        ), 1000);

    }
}