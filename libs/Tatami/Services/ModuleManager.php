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
	/**
	 * Array of all modules
	 * @var array
	 */
	$modules = array(),
	    
	$activeModulesList = array(),
	    
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
    
    /**
     * Parses module name from presenter name
     * @param string $presenterName
     * @return string 
     */
    public function getModuleName($presenterName)
    {
        return $this->parseModuleName($presenterName);
    }

    /**
     * Construct Module classname from presenter name
     * unused
     * @param type $presenterName
     * @return type 
     */
    public function getModuleClass($presenterName)
    {
        return 'Tatami\Modules\\'.$this->parseModuleName($presenterName).'Module';
    }

    public function getModuleNameFromPresenterClass($className)
    {
	return substr($className, 0, strpos($className, 'Module\\'));
    }
    
    private function parseModuleName($name)
    {
        $pieces = \explode(':', $name);
        return $pieces[0];
    }
    
    private function formatModuleClass($moduleName)
    {
	return '\Tatami\Modules\\'.$moduleName; 
    }
    
    private function findModules()
    {
	if($this->cache->offsetExists('modules'))
            $modules = $this->cache->offsetGet('modules');
        else
        {
            $classes = $this->robotLoader->getIndexedClasses();
            $modules = array();
            foreach($classes as $class => $file)
            {
		if(class_exists($class))
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
            }
            $this->cache->offsetSet('modules', $modules);
        }
	return $modules;
    }
    
    public function getModuleEntities()
    {
	return $this->entityManager->getRepository('Entity\Module')->findAll();
    }
    
    private function initializeModules()
    {
	$modules = $this->findModules();
	$this->modules = array();
	$this->activeModulesList = array();
	foreach($modules as $moduleClass)
	{
	    $module = new $moduleClass;
	    if(!$this->isModuleInstalled($module))
		$this->installModule($module);
	    
	    if($this->isModuleActive($module))
	    {
		$module->setActive();
		$this->registerModule($module->getName());
		//signup module for events
		$this->eventManager->addSubscriber(Events\Event::DASHBOARD_LOAD, $module);
		$this->eventManager->addSubscriber(Events\Event::PERMISSIONS_LOAD, $module);
		$this->eventManager->addSubscriber(Events\Event::ROUTES_LOAD, $module);
	    }
	    
	    $this->modules[$module->getName()] = $module;
	}
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
	return $this;
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
	$moduleEntity = $this->entityManager->getRepository('Entity\Module')->findOneByName($moduleName);
	$moduleEntity->setActive(true);
	$this->entityManager->persist($moduleEntity);
	$this->entityManager->flush();
	$this->initializeModules();
	return $this;
    }

    public function deactivateModule($moduleName)
    {
	$moduleEntity = $this->entityManager->getRepository('Entity\Module')->findOneByName($moduleName);
	$moduleEntity->setActive(false);
	$this->entityManager->persist($moduleEntity);
	$this->entityManager->flush();
	$this->initializeModules();
	return $this;
    }
    
    public function deleteModule($moduleName)
    {
	$moduleEntity = $this->entityManager->getRepository('Entity\Module')->findOneByName($moduleName);
	$moduleEntity->setActive(false);
	$this->entityManager->persist($moduleEntity);
	$this->entityManager->flush();
	return $this;
    }

    public function registerModule($moduleName)
    {
        $this->activeModulesList[] = $moduleName;
	return $this;
    }
    
    public function onApplicationStart($dispatcher, $args)
    {
	$this->initializeModules($dispatcher);
    }
    
    public function search($search)
    {
	$searchResults = array();
	foreach($this->$modules as $module)
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
    
    public function getModules() 
    {
	return $this->modules;
    }

    public function getActiveModules()
    {
	return array_intersect_key($this->modules, array_flip($this->activeModulesList));
    }

}