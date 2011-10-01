<?php
namespace TatamiModule;
/**
 * Description of RolesPresenter
 *
 * @author Martin
 */
class RolesPresenter extends BasePresenter
{
    public
	/** @persistent */
	$roleId
    ;
    
    public function createComponentPermissionsBrowser($name)
    {
        $browser = new \Tatami\Components\PermissionsBrowser($this, $name);
        $browser->setRepository($this->em->getRepository('UserRole'));
    }
    
    public function createComponentGridRoles($name)
    {
	$persister = new \Gridder\Persisters\SessionPersister($this->getSession('grid-permissions'));
	$repository = $this->em->getRepository('UserRole');
	$source = new \Gridder\Sources\RepositorySource($repository);
	
	$grid = new \Gridder\Gridder($source, $persister, $this, $name);
	$grid->addColumn('name');
	$grid->addColumn('permissions', 'entityChild')->setPath('permissions');
	
	$ac = $grid->addActionColumn('actions');
	$ac->addAction('edit', 'edit!', true)->setIcon('normal edit')->hideTitle()
		->onActionRender[] = callback($this, 'checkRole'); 
	
	$ac->addAction('delete', 'delete!', true)->setIcon('normal delete')->hideTitle()
		->onActionRender[] = callback($this, 'checkRole'); 
    }
    
    public function checkRole($id, \Entity\UserRole $role, $title, $output)
    {
	if($role->name == 'Admin') 
	{
	    if($title == 'edit')
	    {
		return 'Can\'t edit';
	    }
	    if($title == 'delete')
	    {
		return 'Can\'t delete';
	    }
	}
	else
	{
	    return $output;
	}
    }
    
    public function createComponentFormAddUserRole($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addText('name', 'Role name')->setRequired('Please fill in %label');
	$userRoles = $this->em->getRepository('UserRole')->fetchPairs('id', 'name');
	unset($userRoles[1]);
	$items = array(null => 'none') + $userRoles;
	$form->addSelect('template', 'Copy from', $items);
	$form->addSubmit('btnSubmit', 'Create');
	$form->onSuccess[] = callback($this, 'formAddUserRoleSubmitted');
    }
    
    public function formAddUserRoleSubmitted(\Nette\Forms\Form $form)
    {
	$values = $form->values;
	var_dump($values);
	
	$template = $values->template;    
	$roleName = $values->name;
	
	$role = new \Entity\UserRole();
	$role->setName($roleName);
	
	if($template != '')
	{
	    $templateRole = $this->em->getRepository('UserRole')->find($template);
	    var_dump($templateRole);
	    $this->em->detach($templateRole);
	    var_dump($templateRole);exit;
	}
    }
    
    public function createComponentFormEditPermissions($name)
    {
	$form = new \Tatami\Forms\AjaxForm($this, $name);
	
	$form->addHidden('roleId', $this->roleId);
	
	$permissions = $this->context->moduleManager->getPermissions();
	$permissionsContainer = $form->addContainer('permissions');
	foreach($permissions as $module => $permissionsArray)
	{
	    foreach($permissionsArray as $resource => $privileges)
	    {
		$container = $permissionsContainer->addContainer($resource);
		foreach($privileges as $privilege => $privilegeDescription)
		{
		    $container->addCheckbox($privilege, $privilegeDescription);		    
		}
	    }
	}
	
	$rolePermissions = $this->em->getRepository('UserRole')->getPermissionsForRole($this->roleId);
	$form->setDefaults(array('permissions' => $rolePermissions));
	$form->addSubmit('btnSubmit', 'Save');
	$form->onSuccess[] = callback($this, 'formEditPermissionsSubmitted');
    }
    
    private function findPermission($permissions, $resource, $privilege)
    {
	foreach($permissions as $permission)
	{
	    if($permission->resource->name == $resource and $permission->privilege == $privilege)
	    {
		return $permission;
	    }
	}
	return null;
    }
    
    public function formEditPermissionsSubmitted($form)
    {
	$values = $form->values;
	
	$permissions = $this->em->getRepository('Permission')->findAll();
	$role = $this->em->getRepository('UserRole')->find($values->roleId);
	
	foreach($values->permissions as $resource => $privileges)
	{
	    foreach($privileges as $privilege => $allowed)
	    {
		if($allowed)
		{
		    $permission = $this->findPermission($permissions, $resource, $privilege);
		    if(!$role->hasPermission($permission))
		    {
			$role->addPermission($permission);
		    }
		}
		else
		{
		    $permission = $this->findPermission($permissions, $resource, $privilege);
		    $role->deletePermission($permission);
		}
	    }
	}
	$this->em->flush();
	$this->roleId = null;
	$this->popupOff();
	$this->invalidateControl('grid');
    }
    
    public function handleAdd()
    {
	$this->view = 'add';
	$this->popupOn();
    }
    
    public function handleEdit($id)
    {
	$this->roleId = $id;
	$this->view = 'edit';
	$this->popupOn();
    }
    
    public function handleDelete($id)
    {
	
    }
}