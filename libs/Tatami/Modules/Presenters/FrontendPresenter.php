<?php
namespace Tatami\Presenters;
use Tatami\Modules\IModule;
/**
 * Description of FrontendPresenter
 *
 * @author Martin
 */
class FrontendPresenter extends BasePresenter implements IFrontendModulePresenter
{
    protected 
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
}