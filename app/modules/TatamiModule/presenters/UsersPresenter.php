<?php
namespace TatamiModule;
/**
 * Description of UsersPresenter
 *
 * @author Martin
 */
class UsersPresenter extends \Tatami\Modules\ModulePresenter
{
    protected $toolbar = 'users';
    
    public function actionDefault()
    {
        //var_dump($this->getUser()->getIdentity()->role);exit;
    }
}