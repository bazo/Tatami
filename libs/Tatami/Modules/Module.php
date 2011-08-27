<?php
namespace Tatami\Modules;
use Tatami\Events;
/**
 * Description of Module
 *
 * @author Martin
 */
abstract class Module extends CoreModule
{
    
    protected
        $name = null,
        $widgetName = null,
        $routes = array(),
        $permissions = array(),
        $entryPoint = null
    ;
    
    public function loadDashboardWidget(\Tatami\Widgets\WidgetManager &$widgetManager, $args)
    {
        $widgetManager->addWidget(new static::$widgetName());
    }
}