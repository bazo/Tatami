<?php
namespace Tatami\ServiceFactories;
use Tatami\Modules;
class PresenterFactory extends \Nette\Application\PresenterFactory
{
    private
	$moduleManager,
	$context
    ;


    public function __construct($baseDir, \Nette\DI\IContainer $context)
    {
	parent::__construct($baseDir, $context);
	$this->context = $context;
    }
    
    public static function create(\Nette\DI\IContainer $context)
    {
	$baseDir = $context->params['appDir'];
	return new self($baseDir, $context);
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
	 if($presenter instanceof Modules\ModulePresenter and (isset($this->context->params['installed']) and $this->context->params['installed'] == true))
	 {
             $moduleManager = $this->context->getService('moduleManager');
	     $moduleName = $moduleManager->getModuleNameFromPresenterClass($class);
	     $module = $moduleManager->getModule($moduleName);
	     $presenter->setModule($module);
	 }
         return $presenter;
     }
}