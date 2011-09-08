<?php
namespace Tatami\ServiceFactories;
use Nette\DI;

class ShortcutsManagerFactory 
{
    public static function create(DI\Container $container)
    {
	$robotLoader = $container->getService('robotLoader');
	//$cacheStorage = $container->getService('cacheStorage');
	
	$shortcutsManager = new \ShortcutsManager($robotLoader);
	return $shortcutsManager;
    }
}