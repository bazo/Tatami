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
	try
	{
	    $entityManager = $container->getService('entityManager');
	}
	catch(\InvalidArgumentException $e) //on purpose, entity manager is not needed all the time
	{
	    $entityManager = null;
	}
	$robotLoader = $container->getService('robotLoader');
	$cacheStorage = $container->getService('cacheStorage');
	
	$cache =  new \Nette\Caching\Cache($cacheStorage, self::$namespace);
	$eventManager = $container->getService('eventManager');
	$moduleManager = new \Tatami\Modules\ModuleManager($robotLoader, $eventManager, $cache, $entityManager);
	$eventManager->addSubscriber(\Tatami\Events\Event::APPLICATION_STARTUP, $moduleManager);
	
	return $moduleManager;
    }
}