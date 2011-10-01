<?php
namespace Tatami\Security;

use Nette\Security\Permission;
class Acl extends Permission
{
    public static function create(\Nette\DI\IContainer $container)
    {
        return new self($container->getService('entityManager'), 
                $container->getService('moduleManager'),
                $container->getService('eventManager'));
    }
    
    private function __construct(\Doctrine\ORM\EntityManager $entityManager, 
            \Tatami\Modules\ModuleManager $moduleManager,
            \Tatami\Events\EventManager $eventManager)
    {
        $resources = $entityManager->getRepository('Resource')->findAll();
        foreach($resources as $resource)
        {
            $this->addResource($resource->getName());
        }
        
        $roles = $entityManager->getRepository('UserRole')->findAll();
        foreach($roles as $role)
        {
	    $this->addRole($role->getName());
	    if($role->name == 'Admin')
	    {
		$this->allow($role->getName(), Permission::ALL, Permission::ALL);
	    }
	    else
	    {
		foreach($role->getPermissions() as $permission)
		{
		    $this->allow($role->getName(), $permission->getResource()->getName(), $permission->getPrivilege());
		}
	    }
        }
        $eventManager->fireEvent(\Tatami\Events\Event::PERMISSIONS_LOAD, $this);
    }
}