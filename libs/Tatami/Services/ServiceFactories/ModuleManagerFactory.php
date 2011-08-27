<?php
namespace Tatami\ServiceFactories;
use Nette\DI;
/**
 * ModuleManagerFactory
 *
 * @author Martin Bažík
 */
class ModuleManagerFactory 
{
    private static
	$namespace = 'Tatami.ModuleManager'
    ;
    
    public static function create(DI\Container $container)
    {
	$entityManager = $container->getService('EntityManager');
	$robotLoader = $container->getService('robotLoader');
	$cacheStorage = $container->getService('cacheStorage');
	
	$cache =  new \Nette\Caching\Cache($cacheStorage, self::$namespace);
	$eventManager = $container->getService('EventManager');
	$moduleManager = new \Tatami\Modules\ModuleManager($entityManager, $robotLoader, $eventManager, $cache);
	$eventManager->addSubscriber(\Tatami\Events\Event::APPLICATION_STARTUP, $moduleManager);
	
	return $moduleManager;
    }
}