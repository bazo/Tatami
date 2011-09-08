<?php
namespace TatamiModule;
use Nette\Environment;

abstract class SecuredPresenter extends TatamiPresenter
{

    public function  startup()
    {
        parent::startup();
        if(!$this->getUser()->isLoggedIn())
        {
            $this->redirect(':Tatami:Login:');
        }
        
        $resource = 'user';
        $privilege = 'add';
        if(!$this->getUser()->isAllowed($resource, $privilege ))
        {
            //log the attempt to do forbidden things
            $this->flash('You do not have sufficient privileges for this action. Your attempt has been logged', 'warning');
        }
    }

    public function handleLogout()
    {
        $user = $this->getUser();
        $user->logout(true);
        $this->flash('You were successfuly logged out!', 'ok');
        $this->redirect('Login');
    }
}