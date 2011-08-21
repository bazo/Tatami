<?php
namespace TatamiModule;

class LoginPresenter extends TatamiPresenter
{
    public function startup()
    {
        parent::startup();
	if($this->getUser()->isLoggedIn()) $this->redirect(':tatami:dashboard:');
    }

    public function createComponentLoginForm($name)
    {
        $form = new \Tatami\Forms\LoginForm($this, $name);
        $form->onSuccess[] = callback($this, 'formSubmitted');
    }

    public function formSubmitted(\Nette\Application\UI\Form $form)
    {
        $values = $form->getValues();
	try
	{
	    $this->getUser()->login($values->login, $values->password);
	    if ($values->remember) 
	    {
		$this->getUser()->setExpiration('+ 14 days', FALSE);
	    } else {
		$this->getUser()->setExpiration('+ 60 minutes', TRUE);
	    }
	    $this->redirect(':tatami:dashboard:');
	}
	catch(\Nette\Security\AuthenticationException $e)
	{
	    $form->addError($e->getMessage());
	}
    }
}
