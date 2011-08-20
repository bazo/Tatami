<?php
namespace Tatami\Modules;
use Tatami\Events;
/**
 * Description of CoreModule
 *
 * @author Martin
 */
abstract class CoreModule extends \Tatami\Subscriber implements IModule
{

    protected
        $moduleName = null,
	    
        $widgetName = null,
	    
        $routes = array(),
	    
        $permissions = array(),
	    
        $entryPoint = null,
	    
        $navigation = array(),
	    
	$toolbar = array(),    
	    
	/** @var \Doctrine\ORM\EntityManager */
	$entityManager
    ;

    public function onDashboardLoad(&$dispatcher, $args)
    {
	return $this->loadDashboardWidget($dispatcher, $args);
    }
    
    public function onRoutesLoad(&$dispatcher, $args)
    {
	return $this->loadRoutes($dispatcher, $args);
    }
    
    public function onApplicationStartup(&$dispatcher, $args)
    {
	return $this->onApplicationStart($dispatcher, $args);
    }
    
    public function onApplicationStart(\Nette\Application\Application $application, \Nette\Configurator $configurator)
    {
	
    }

    public function getName()
    {
        return $this->moduleName != null ? $this->moduleName :  \str_replace('Module', '', $this->getReflection()->getShortName());
    }
    
    public function loadRoutes(\Nette\Application\IRouter $router, $args)
    {
    }

    function loadDashboardWidget(\Tatami\Widgets\WidgetManager &$widgetManager, $args)
    {
        $widgetManager->addWidget(new $this->widgetName());
    }

    protected function onInstall()
    {

    }

    protected function onUninstall()
    {

    }

    protected function onActivate()
    {

    }

    protected function onDeactivate()
    {

    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function getNavigation()
    {
        return $this->navigation;
    }

    public function getToolbar($toolbarName)
    {
	return $this->toolbar[$toolbarName];
    }
    
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function getEntryPoint()
    {
        return $this->entryPoint != null ? $this->entryPoint : ':'.$this->getName().':Default:default';
    }
    
    public function getSearchResults($expression)
    {
	
    }
}