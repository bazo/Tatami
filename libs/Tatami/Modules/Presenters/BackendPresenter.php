<?php
namespace Tatami\Presenters;
use Tatami\Modules\IModule;
use Tatami\Presenters\SecuredPresenter;
/**
 * Description of ModulePresenter
 *
 * @author Martin
 */
abstract class BackendPresenter extends SecuredPresenter implements IBackendModulePresenter
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
	$this->em = $this->context->getService('entityManager');
        $this->eventManager = $this->context->getService('eventManager');
	$this->moduleManager = $this->context->getService('moduleManager');
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
	$shortcutsManager = $this->context->getService('shortcutsManager');
        $shortcuts = new \Tatami\Components\Shortcuts($this, $name, $shortcutsManager);
    }
}