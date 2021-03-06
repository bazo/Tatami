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
	$eventManager,
	    
	$endpoint = 'http://modules.tatami.bazik.biz'
    ;

    /**
     * Construct
     * @param Doctrine\ORM\EntityManager $entityManager 
     */
    public function __construct(\Nette\Loaders\RobotLoader $robotLoader, \Tatami\Events\EventManager $eventManager, \Nette\Caching\Cache $cache, \Doctrine\ORM\EntityManager $entityManager = null)
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
	return '\Tatami\Modules\\'.$moduleName.'Module'; 
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
		    unset($reflection);
		}
            }
            $this->cache->offsetSet('modules', $modules);
        }
	return $modules;
    }
    
    public function getModuleEntities()
    {
	return $this->entityManager->getRepository('Module')->findAll();
    }
    
    public function initializeModules()
    {
	$modules = $this->findModules();
	$this->modules = array();
	$this->activeModulesList = array();
	foreach($modules as $moduleClass)
	{
	    $module = new $moduleClass;
	    if(!$this->isModuleInstalled($module))
		$this->installModule($module);
	    
            /*
            if($module instanceof IEssentialModule)
                $this->activateModule ($module->getName());
            */
	    if($this->isModuleActive($module))
	    {
                
		$module->setActive();
		$this->registerModule($module->getName());
		//signup module for events
		//$this->eventManager->addSubscriber(Events\Event::DASHBOARD_LOAD, $module);
		//$this->eventManager->addSubscriber(Events\Event::PERMISSIONS_LOAD, $module);
		$this->eventManager->addSubscriber(Events\Event::ROUTES_LOAD, $module);
	    }
	    $this->modules[$module->getName()] = $module;
	}
    }
    
    
    private function createSchema($entities)
    {
	$schemaTool =  new \Doctrine\ORM\Tools\SchemaTool($this->entityManager);
	foreach($entities as $entityClass)
	{
	    $classes[] = $this->entityManager->getClassMetadata($entityClass);
	}
	if(!empty($classes))
	{
	    $schemaTool->createSchema($classes);
	}
    }
    
    private function installResources($permissions)
    {
	foreach($permissions as $resourceName => $privileges)
	{
	    $resource = new \Entity\Resource($resourceName);
            
            foreach($privileges as $privilege => $privilegeText)
            {
                $permission = new \Entity\Permission;
                $permission->setResource($resource)->setPrivilege($privilege)
                        ->setPrivilegeText($privilegeText);
		$this->entityManager->persist($permission);
            }        
	}
    }
    
    private function installWidgets($widgets, $module)
    {
	foreach($widgets as $widgetName => $widgetClass)
	{
	    $widget = new \Entity\Widget;
	    $widget->setModule($module);
	    $widget->setName($widgetName);
	    $widget->setClass($widgetClass);
	    $widget->setActive(false);
	    $this->entityManager->persist($widget);
	}
    }
    
    public function installModule($module)
    {
	$moduleEntity = new \Entity\Module();
	$moduleEntity->setName($module->getName());
	$moduleEntity->setInstalled(true);
	$moduleEntity->setActive(false);
	$this->entityManager->flush();
	try
	{
	   $this->entityManager->persist($moduleEntity);
	   $module instanceof CoreModule;
	   
	   $entities = $module->getEntities();
	   $this->createSchema($entities);
	   
	   $permissions = $module->getPermissions();
	   $this->installResources($permissions);
	   
	   $widgets = $module->getWidgets();
	   $this->installWidgets($widgets, $moduleEntity);
	   
	   $this->entityManager->flush();
	}
	catch(\PDOException $e)
	{
	    var_dump($e->getMessage());exit;
	   $this->entityManager->detach($moduleEntity);
	}
	return $this;
    }

    public function isModuleInstalled($module)
    {
	try
	{
	   if($module instanceof IModule) $name = $module->getName();
	   else $name = $module;
	   $moduleEntity = $this->entityManager->getRepository('Module')->findOneByName($name);
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
        $moduleEntity = $this->entityManager->getRepository('Module')->findOneByName($module->getName());
        return $moduleEntity->getActive();
    }

    public function activateModule($moduleName)
    {
	$moduleEntity = $this->entityManager->getRepository('Module')->findOneByName($moduleName);
	$moduleEntity->setActive(true);
	$this->entityManager->persist($moduleEntity);
	$this->entityManager->flush();
	//$this->initializeModules();
	return $this;
    }

    public function deactivateModule($moduleName)
    {
	$moduleEntity = $this->entityManager->getRepository('Module')->findOneByName($moduleName);
	$moduleEntity->setActive(false);
	$this->entityManager->persist($moduleEntity);
	$this->entityManager->flush();
	//$this->initializeModules();
	return $this;
    }
    
    public function deleteModule($moduleName)
    {
	$moduleEntity = $this->entityManager->getRepository('Module')->findOneByName($moduleName);
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
	$moduleName = ucfirst(mb_strtolower($moduleName));
	$modules = $this->getModules();
	if(isset($modules[$moduleName]))
	    return $modules[$moduleName];
	else{
	    $moduleClass = $this->formatModuleClass($moduleName);
	    return new $moduleClass;
	}
    }
    
    public function getModules() 
    {
	return $this->modules;
    }

    public function getActiveModules()
    {
	return array_intersect_key($this->modules, array_flip($this->activeModulesList));
    }
    
    public function getAvailableModules($endPoint = null)
    {
	$endPoint = $endPoint != null ? $endPoint : $this->endpoint;
	$response = file_get_contents($endPoint);
	return json_decode($response);
    }
    
    public function moduleExists($moduleName)
    {
	return isset($this->modules[ucfirst($moduleName)]);
    }
    
    public function getPermissions()
    {
        $permissions = array();
        foreach($this->getActiveModules() as $module)
        {
            $permissions[$module->getName()] = $module->getPermissions();
        }
        return $permissions;
    }

}