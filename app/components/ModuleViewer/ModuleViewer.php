<?php
namespace Tatami\Components;
use Tatami\Modules\ModuleManager, Nette\ComponentModel\IContainer;
/**
 * Description of ModuleViewer
 *
 * @author Martin
 */
class ModuleViewer extends BaseControl
{
    private
	/** @var ModuleManager */
	$moduleManager,
	    
	$view = 'grid'
    ;
    
    public 
	/** @var \Nette\Callback */
	$onModuleStateChange
    ;


    /**
     *
     * @param Nette\ComponentModel\IContainer $parent
     * @param type $name
     * @param ModuleManager $moduleManager 
     */
    public function __construct(IContainer $parent = NULL, $name = NULL)
    {
	parent::__construct($parent, $name);
    }
    
    public function setModuleManager(ModuleManager $moduleManager)
    {
	$this->moduleManager = $moduleManager;
    }
    
    public function handleActivateModule($name)
    {
	$this->moduleManager->activateModule($name);
	$this->invalidateControl('view');
	$this->onModuleStateChange->invoke();
    }
    
    public function handleDeactivateModule($name)
    {
	$this->moduleManager->deactivateModule($name);
	$this->invalidateControl('view');
	$this->onModuleStateChange->invoke();
    }
    
    public function handleDeleteModule($name)
    {
	$this->moduleManager->deleteModule($name);
	$this->invalidateControl('view');
	$this->onModuleStateChange->invoke();
    }
    
    public function render()
    {
	$this->template->setFile(__DIR__.'/moduleViewer.latte');
	
	$this->template->modules = $this->moduleManager->getModules();
	$this->template->render();
    }
}
