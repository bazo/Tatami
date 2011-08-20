<?php
namespace Tatami\Modules;
use Tatami\Events;
/**
 * Description of ModuleManager
 *
 * @author Martin
 */

final class ModuleManager extends \Tatami\Subscriber
{
    private 
	$modules = array(),
	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	$entityManager,
	    
	/** @var \Nette\Loaders\RobotLoader */
	$robotLoader,
	    
	/** @var \Nette\Caching\Cache */    
	$cache,
	    
	/** @var \Tatami\Events\EventManager */
	$eventManager
    ;

    /**
     * Construct
     * @param Doctrine\ORM\EntityManager $entityManager 
     */
    public function __construct(\Doctrine\ORM\EntityManager $entityManager, \Nette\Loaders\RobotLoader $robotLoader, \Tatami\Events\EventManager $eventManager, \Nette\Caching\Cache $cache)
    {
	$this->entityManager = $entityManager;
	$this->robotLoader = $robotLoader;
	$this->eventManager = $eventManager;
	$this->cache = $cache;
    }
    
    public function getModuleName($presenterName)
    {
	//var_dump($presenterName);exit;
        return $this->parseModuleName($presenterName);
    }

    public function getCurrentModuleClass($presenterName)
    {
        return 'Tatami\Modules\\'.$this->parseModuleName($presenterName).'Module';
    }

    public function getModuleNameFromPresenterClass($className)
    {
	return substr($className, 0, strpos($className, 'Module\\'));
    }
    
    private function formatModuleClass($moduleName)
    {
	return '\Tatami\Modules\$moduleName'; 
    }
    
    public function getModules()
    {
	if($this->cache->offsetExists('modules'))
            $modules = $this->cache->offsetGet('modules');
        else
        {
            $classes = $this->robotLoader->getIndexedClasses();
            $modules = array();
            foreach($classes as $class => $file)
            {
                $reflection = new \Nette\Reflection\ClassType($class);
                if($reflection->implementsInterface('Tatami\Modules\IModule'))
                {
                    if(!($reflection->isAbstract() or $reflection->isInterface()))
		    {
			$module = new $class;
			$modules[$module->getName()] = $module;
		    }
                        
                }
            }
            $this->cache->offsetSet('modules', $modules);
        }
	return $modules;
    }
    
    public function getActiveModules()
    {
	$result = array();
	foreach ($this->getModules() as $module)
	{
	    if($this->isModuleActive($module)) $result[] = $module;
	}
	return $result;
    }
    
    private function initializeModules()
    {
	$modules = $this->getModules();
	foreach($modules as $moduleClass)
	{
	    $module = new $moduleClass;
	    if(!$this->isModuleInstalled($module))
		$this->installModule($module);
	    if($this->isModuleActive($module))
	    {
		$this->registerModule($module->getName(), $module->getEntryPoint());
		//signup module for events
		$this->eventManager->addSubscriber(Events\Event::DASHBOARD_LOAD, $module);
		$this->eventManager->addSubscriber(Events\Event::PERMISSIONS_LOAD, $module);
		$this->eventManager->addSubscriber(Events\Event::ROUTES_LOAD, $module);
	    }
	}
    }
    
    private function parseModuleName($name)
    {
        $pieces = \explode(':', $name);
        return $pieces[0];
    }

    public function installModule($module)
    {
	$moduleEntity = new \Entity\Module();
	$moduleEntity->setName($module->getName());
	$moduleEntity->setInstalled(true);
	$moduleEntity->setActive(false);
	try
	{
	   $this->entityManager->persist($moduleEntity);
	   $this->entityManager->flush();
	}
	catch(\PDOException $e)
	{
	   $this->entityManager->detach($moduleEntity);
	}
    }

    public function isModuleInstalled($module)
    {
	try
	{
	   $moduleEntity = $this->entityManager->getRepository('Entity\Module')->findOneByName($module->getName());
	}
	catch(\PDOException $e)
	{
	   return false;
	}
	if($moduleEntity == null)
	   return false;
	else return true;
    }

    public function isModuleActive(IModule $module)
    {
        $moduleEntity = $this->entityManager->getRepository('Entity\Module')->findOneByName($module->getName());
        return $moduleEntity->getActive();
    }

    public function activateModule($moduleName)
    {
	$module = new $this->formatModuleClass($moduleName);    
    }

    public function deactivateModule($moduleName)
    {
	$module = new $this->formatModuleClass($moduleName);    
    }

    public function registerModule($moduleName, $entryPoint)
    {
        $this->modules[$moduleName] = $entryPoint;
    }
    
    public function onApplicationStart($dispatcher, $args)
    {
	$this->initializeModules($dispatcher);
    }
    
    public function search($search)
    {
	$searchResults = array();
	foreach($this->getActiveModules() as $module)
	{
	    $searchResults += $module->getSearchResults($search);
	}
	return $searchResults;
    }
    
    public function getModule($moduleName)
    {
	$modules = $this->getModules();
	return $modules[$moduleName];
    }
}