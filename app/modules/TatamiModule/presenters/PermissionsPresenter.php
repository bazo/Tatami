<?php
namespace TatamiModule;
/**
 * Description of PermissionsPresenter
 *
 * @author Martin
 */
class PermissionsPresenter extends \Tatami\Modules\ModulePresenter
{
    public function actionDefault()
    {
        var_dump($this->moduleManager->getPermissions());exit;
    }
}