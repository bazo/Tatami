<?php
namespace Tatami\ServiceFactories;
use Tatami\Modules;
class PresenterFactory extends \Nette\Application\PresenterFactory
{
    private
	$moduleManager,
	$context
    ;


    public function __construct($baseDir, \Nette\DI\IContainer $context, Modules\ModuleManager $moduleManager)
    {
	parent::__construct($baseDir, $context);
	$this->moduleManager = $moduleManager;
	$this->context = $context;
    }
    
    public static function create(\Nette\DI\IContainer $context)
    {
	$moduleManager = $context->getService('ModuleManager');
	$baseDir = $context->params['appDir'];
	return new self($baseDir, $context, $moduleManager);
    }
    
    /**
      * Create new presenter instance.
      * @param  string  presenter name
      * @return IPresenter
      */
     public function createPresenter($name)
     {
         $class = $this->getPresenterClass($name);
         $presenter = new $class;
         $presenter->setContext($this->context);
	 if($presenter instanceof Modules\ModulePresenter)
	 {
	     $moduleName = $this->moduleManager->getModuleNameFromPresenterClass($class);
	     $module = $this->moduleManager->getModule($moduleName);
	     $presenter->setModule($module);
	 }
         return $presenter;
     }
}