<?php
namespace Tatami\Modules;
/**
 * Description of ModulePresenter
 *
 * @author Martin
 */
abstract class ModulePresenter extends \TatamiModule\SecuredPresenter
{
    protected
        /** @var \Tatami\Modules\ModuleManager */
        $moduleManager,

        /** @var \Doctrine\ORM\EntityManager */
        $em,
            
        /** @var \Tatami\Events\EventManager */
        $eventManager,
            
        $activeModule = '',
	    
	$toolbar = null,
	
	/** @var IModule */    
	$module
    ;
    
    public function setModule(IModule $module)
    {
	$this->module = $module;
	return $this;
    }
    
    public function getModule()
    {
	return $this->module;
    }
    
    public function startup()
    {
	parent::startup();
	if(!$this->module->isActive())
	    throw new \Nette\Application\BadRequestException(sprintf('Module %s not activated', $this->module->getName()));
	$this->em = $this->context->getService('EntityManager');
        $this->eventManager = $this->context->getService('EventManager');
	$this->moduleManager = $this->context->getService('ModuleManager');
    }
    
    protected function createComponentNavigation($name)
    {
	$navigationItems = array();
	foreach($this->moduleManager->getActiveModules() as $module)
	{
	    $navigationItems[$module->getName()] = $module->getNavigation();
	}
	$navigation = new \Tatami\Components\Navigation\Navigation($this, $name);
	$navigation->build($navigationItems);
	$navigation->setCurrentModule($this->moduleManager->getModuleName($this->name));
    }
    
    protected function createComponentSearchBox($name)
    {
	$form = new \Tatami\Forms\SearchForm($this, $name);
	$form->onSuccess[] = callback($this, 'searchBoxSubmitted');
    }
    
    public function searchBoxSubmitted(\Nette\Application\UI\Form $form)
    {
	$values = $form->getValues();
	$search = $values['search'];
	$this->redirect(':tatami:search:', array('search' => $search));
    }
    
    protected function createComponentToolbar($name)
    {
	$toolbar = new \Tatami\Components\Toolbar($this, $name);
	if($this->toolbar != null)
	{
	    $toolbarArray = $this->module->getToolbar($this->toolbar);
	    $toolbar->build($toolbarArray);
	}
    }
    
    protected function createComponentShortcuts($name)
    {
	$shortcutsManager = $this->context->getService('ShortcutsManager');
        $shortcuts = new \Tatami\Components\Shortcuts($this, $name, $shortcutsManager);
    }
    
    protected function createComponentCss($name)
    {
	$params = $this->context->params;
	$basePath = $this->getHttpRequest()->getUrl()->basePath;
	$css = new \Tatami\Components\WebLoader\CssLoader($this, $name, $params['wwwDir'], $basePath);
        $css->sourcePath = $params['assetsDir'] . "/css";
	
        $css->tempUri = $this->getHttpRequest()->getUrl()->baseUrl . "webtemp";
        $css->tempPath = $params['wwwDir'] . "/webtemp";
    }

    protected function createComponentJs($name)
    {
	$params = $this->context->params;
        
	$js = new \Tatami\Components\WebLoader\JavaScriptLoader($this, $name);
        $js->tempUri = $this->getHttpRequest()->getUrl()->baseUrl . "webtemp";
        $js->sourcePath = $params['assetsDir'] . "/js";
	$js->tempPath = $params['wwwDir'] . "/webtemp";
    }
    
    protected function createComponentAssetsLoader($name)
    {
	$params = $this->context->params;
	$assetsLoader = new \Tatami\Components\AssetsLoader\AssetsLoader($this, $name);
	$assetsLoader->setModuleManager($this->moduleManager)
		    ->setWwwDir($params['wwwDir'])
		    ->setBasePath($basePath = $this->getHttpRequest()->getUrl()->basePath)
		    ->setWebtemp($params['wwwDir'] . "/webtemp")
		    ->setTempUrl($this->getHttpRequest()->getUrl()->baseUrl . "webtemp");
    }
    
}