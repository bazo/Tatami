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
	    
	$entities = array(),    
	   
	$widgets = array(),    
	    
        $routes = array(),
	    
        $permissions = array(),
	    
        $entryPoint = null,
	    
	$settingsPoint = null,
	    
        $navigation = array(),
	    
	$toolbar = array(),    
	    
	/** @var \Doctrine\ORM\EntityManager */
	$entityManager,
	    
	$icon = 'default',
	    
	$active
    ;
    
    public function getFile()
    {
	$reflection = new \ReflectionClass(get_called_class());
	$file = $reflection->getFileName();
	unset($reflection);
	return $file;
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

    public function getEntities()
    {
	return $this->entities;
    }
    
    public function getWidgets()
    {
	return $this->widgets;
    }
    
    public function getName()
    {
        return $this->moduleName != null ? $this->moduleName :  \str_replace('Module', '', $this->getReflection()->getShortName());
    }
    
    public function getData()
    {
	$data = array();
	return $data;
    }
    
    public function loadRoutes(\Nette\Application\IRouter $router, $args)
    {
	
    }

    public function onInstall()
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
    
    public function getSettingsPoint()
    {
	return $this->settingsPoint;
    }
    
    public function getIcon()
    {
	return $this->icon;
    }
    
    public function isActive()
    {
	return $this->active == true ? true : false;
    }
    
    public function setActive()
    {
	$this->active = true;
    }
    
    public function IsEssential()
    {
	return $this->getReflection()->implementsInterface('Tatami\Modules\IEssentialModule');
    }
}