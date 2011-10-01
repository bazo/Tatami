<?php
namespace TatamiModule;
/**
 * Description of UsersPresenter
 *
 * @author Martin
 */
class UsersPresenter extends BasePresenter
{
    
    protected function createComponentFormAddUser($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addText('name', 'Name')->setRequired('Please fill %label.');
	$form->addText('email', 'E-mail')->setRequired('Please fill %label.');
	$userRoles = $this->em->getRepository('UserRole')->fetchPairs('id', 'name');
	$form->addSelect('role', 'Role', $userRoles);
	$form->addSubmit('btnSubmit', 'Create');
	$form->onSuccess[] = callback($this, 'formAddSubmitted');
    }

    public function formAddSubmitted(\Nette\Application\UI\Form $form)
    {
	$values = $form->values;
	try
	{
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
	    $this->invalidateControl('grid');
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
    
    public function handleTest()
    {
	ini_set('max_execution_time', 0);
	$limit = 10000;
	$batchSize = 20;
	$role = $this->em->getRepository('userRole')->find(1);
	for($i = 0; $i <= $limit; $i++)
	{
	    try
	    {
		$user = new \Entity\User;
		$user->setName('meno'.$i);
		$user->setEmail('email'.$i.'@email.hovno');
		$user->setPassword('heslo'.$i);
		$user->setRole($role);
		$this->em->persist($user);
		if (($i % $batchSize) == 0) 
		{
		     $this->em->flush();
		     $this->em->clear();
		     $role = $this->em->getRepository('userRole')->find(1);
		}
	    }
	    catch(\Exception $e) 
	    {
		
	    }
	}
	$this->invalidateControl('usersBrowser');
    }
    
    protected function createComponentGridUsers($name)
    {
	
	$repository = $this->em->getRepository('User');
	
	$persister = new \Gridder\Persisters\SessionPersister($this->getSession('usersGrid'));
	
	$queryBuilder = $this->em->createQueryBuilder()->
	select('u')->from('Entity\User', 'u');
	
	$source = new \Gridder\Sources\RepositorySource($repository, \Gridder\Sources\RepositorySource::HYDRATION_COMPLEX);
	//$source = new \Gridder\Sources\QueryBuilderSource($queryBuilder, \Gridder\Sources\QueryBuilderSource::HYDRATION_COMPLEX);
	
	$grid = new \Gridder\Gridder($source, $persister, $this, $name);
	$grid->autoAddFilters = true;
	$grid->addColumn('id');
	$grid->addColumn('name');
	$grid->addColumn('email');
	
	$items = $this->em->getRepository('UserRole')->fetchPairs('id', 'name');
	
	$grid->addColumn('role')->setArrayFilter($items);
	
	$grid->addColumn('permissions', 'entityChild')
		->setPath('role->permissions')
		->disableFilter();
	
	$ac = $grid->addActionColumn('actions');
	$ac->addAction('View', 'view')->setIcon('normal view')->hideTitle();
    }
    
    public function handleAdd()
    {
	$this->view = 'add';
	$this->popupOn();
    }
}