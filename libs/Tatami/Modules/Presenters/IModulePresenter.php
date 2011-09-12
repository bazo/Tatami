<?php
namespace Tatami\Presenters;
use Tatami\Modules\IModule;
/**
 *
 * @author Martin
 */
interface IModulePresenter 
{
    public function setModule(IModule $module);
    
    public function getModule();
}