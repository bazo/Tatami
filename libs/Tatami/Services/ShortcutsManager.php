<?php
use Nette\Utils\Strings;
class ShortcutsManager 
{
    private 
	$modules = array(),
	$presenters = array(),
	$actions = array()
    ;

    public function __construct(Nette\Loaders\RobotLoader $robotLoader) 
    {
	$classes = $robotLoader->getIndexedClasses();
	foreach($classes as $class => $file)
	{
	    if(class_exists($class))
	    {
		$reflection = new \Nette\Reflection\ClassType($class);
		if($reflection->implementsInterface('Tatami\Modules\IModule'))
		{
		    if(!($reflection->isAbstract() or $reflection->isInterface()))
		    {
			$this->modules[] = $this->parseModuleName($reflection->getShortName());
		    }
		}
		if($reflection->isSubclassOf('Tatami\Presenters\BackendPresenter'))
		{
		    $moduleName = $this->parseModuleName($reflection->getNamespaceName());
		    $presenterName = $this->parsePresenterName($reflection->getShortName());
		    $this->presenters[$moduleName][] = $presenterName;
		    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
		    foreach($methods as $method)
		    {
			if((Strings::match($method->name, '/action/')) or (Strings::match($method->name, '/render/')))
			{
			    $this->actions[$presenterName][] = $this->parseActionName($method->name); 
			}
		    }
		}
		unset($reflection);
	    }
	}
    }
    
    private function parseModuleName($name)
    {
	$name = str_replace('Module', '', $name);
	return $name;
    }
    
    private function parsePresenterName($name)
    {
	$name = str_replace('Presenter', '', $name);
	return $name;
    }
    
    private function parseActionName($name)
    {
	$name = str_replace('action', '', $name);
	$name = str_replace('render', '', $name);
	return $name;
    }
    
    public function getModules() 
    {
	return $this->modules;
    }

    public function getPresenters() 
    {
	return $this->presenters;
    }

    public function getActions() 
    {
	return $this->actions;
    }
}