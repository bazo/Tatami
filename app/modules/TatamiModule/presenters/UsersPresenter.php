<?php
namespace TatamiModule;
/**
 * Description of UsersPresenter
 *
 * @author Martin
 */
class UsersPresenter extends \Tatami\Presenters\BackendPresenter
{
    protected $toolbar = 'users';
    
    public function renderDefault()
    {
        $limit = 10;
        $offset = 0;
        $users = $this->em->getRepository('User');//->findBy(array(), null, $limit, $offset);
    }
    
    public function actionAdd()
    {
	$this->popupOn();
    }
    
    protected function createComponentFormAddUser($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addText('name', 'Name')->setRequired('Please fill %label.');
	$form->addText('email', 'E-mail')->setRequired('Please fill %label.');
	$userRoles = $this->em->getRepository('UserRole')->fetchPairs('id', 'name');
	$form->addSelect('role', 'Role', $userRoles);
	$form->addSubmit('btnSubmit', 'Save');
	$form->onSuccess[] = callback($this, 'formAddSubmitted');
    }

    public function formAddSubmitted(\Nette\Application\UI\Form $form)
    {
	$values = $form->values;
	try{
	    $userRole = $this->em->getRepository('UserRole')->find($values->role);
	    $values->role = $userRole;
	    
	    $user = new \Entity\User;
	    $user->setValues((array)$values);
	    $this->em->persist($user);
	    
	    $token = new \Entity\PasswordRecoveryToken;
	    $token->setUser($user);
	    $this->em->persist($token);

	    $this->em->flush();
	    $this->mailBuilder->buildAccountCreatedEmail($user, $token)->send();
	    $this->flash(sprintf('User %s added', $user->name));
	    $this->invalidateControl('usersBrowser');
	    $this->popupOff();
	}
	catch(\PDOException $e)
	{
	    switch($e->getCode())
	    {
		case '23000':
		    $message = 'Duplicate data';
		break;
	    }
	    $form->addError($message);
	    $this->invalidateControl('form');
	}
    }
    

    protected function createComponentUsersBrowser($name)
    {
        $browser = new \Tatami\Components\UsersBrowser($this, $name);
        $repository = $this->em->getRepository('User');
        $browser->setRepository($repository);
    }
}