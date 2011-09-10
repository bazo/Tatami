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
    
    public function createComponentPermissionsEditor($name)
    {
        $editor = new \Tatami\Components\PermissionsEditor($this, $name);
        $editor->setRepository($this->em->getRepository('UserRole'));
    }
}