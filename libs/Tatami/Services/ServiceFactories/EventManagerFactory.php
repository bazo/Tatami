<?php
namespace Tatami\ServiceFactories;
use Nette\DI;
/**
 * EventManagerFactory
 *
 * @author Martin Bažík
 */
class EventManagerFactory 
{
    public static function create(DI\Container $container)
    {
	$robotLoader = $container->getService('robotLoader');
	return new \Tatami\Events\EventManager($robotLoader);
    }
}