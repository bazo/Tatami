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

        $activeModule = '',
	    
	$toolbar = null,
	
	/** @var IModule */    
	$module
    ;
    
    public function setModule(IModule $module)
    {
	$this->module = $module;
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
}