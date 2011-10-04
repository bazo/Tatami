<?php
/**
 * ModulesPresenter
 * @author Martin Bazik
 */
namespace TatamiModule;

class ModulesPresenter extends BasePresenter
{

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
	foreach($availableModules as $module)
	{
	    if($this->moduleManager->isModuleInstalled($module->name))
	    {
		$module->installed = true;
	    }
	    else 
	    {
		$module->installed = false;
	    }
	}
	$this->template->availableModules = $availableModules;
	if($this->isAjax())
	{
	    $this->setLayout('popup');
	}
	$this->invalidateControl('popup');
    }
    
    public function handleDownloadModule($url)
    {
	$destination = $this->context->params['tempDir'].'/'.basename($url);
	$downloader = \Tatami\Tools\Downloader::download($url, $destination);
    }
}