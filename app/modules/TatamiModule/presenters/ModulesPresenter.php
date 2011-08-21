<?php
/**
 * ModulesPresenter
 * @author Martin Bazik
 */
namespace TatamiModule;

class ModulesPresenter extends \Tatami\Modules\ModulePresenter
{

    protected 
	$toolbar = 'modules'
    ;


    public function createComponentModuleViewer($name)
    {
	$moduleViewer = new \Tatami\Components\ModuleViewer($this, $name);
	$moduleViewer->setModuleManager($this->moduleManager);
	$moduleViewer->onModuleStateChange = callback($this, 'refreshMenu');
    }
    
    public function refreshMenu()
    {
	$this->invalidateControl('navigation');
    }
    
    public function renderBrowseModules()
    {
	$availableModules = $this->moduleManager->getAvailableModules();
	var_dump($availableModules);exit;
    }
}