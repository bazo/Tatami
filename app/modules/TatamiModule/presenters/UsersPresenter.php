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
	$form->addText('login', 'Login')->setRequired('Please fill %label.');
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
	    $this->em->flush();
	}
	catch(Exception $e)
	{
	    $form->addError($e->getMessage());
	}
    }
    

    protected function createComponentUsersEditor($name)
    {
        $editor = new \Tatami\Components\UsersEditor($this, $name);
        $repository = $this->em->getRepository('User');
        $editor->setRepository($repository);
    }
}