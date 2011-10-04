<?php
namespace Tatami\ServiceFactories;
use 
    Nette\DI,
     \Doctrine\ORM\EntityRepository;
/**
 * Description of WidgetManager
 *
 * @author Martin
 */
class WidgetsManagerFactory
{
    public static function create(DI\Container $container)
    {
	$widgetRepository = $container->getService('entityManager')->getRepository('Widget');
	return new \Tatami\Widgets\WidgetsManager($widgetRepository);
    }
}