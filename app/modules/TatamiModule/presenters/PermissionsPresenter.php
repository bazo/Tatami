<?php
namespace TatamiModule;
/**
 * Description of PermissionsPresenter
 *
 * @author Martin
 */
class PermissionsPresenter extends \Tatami\Modules\ModulePresenter
{
    public function renderDefault()
    {
    }
    
    public function createComponentPermissionsBrowser($name)
    {
        $browser = new \Tatami\Components\PermissionsBrowser($this, $name);
        $browser->setRepository($this->em->getRepository('UserRole'));
    }
}