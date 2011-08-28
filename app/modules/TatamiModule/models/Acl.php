<?php
namespace Tatami\Security;

use Nette\Security\Permission;
class Acl extends Permission
{
    public static function create(\Nette\DI\IContainer $container)
    {
        return new self($container->getService('EntityManager'), 
                $container->getService('ModuleManager'),
                $container->getService('EventManager'));
    }
    
    private function __construct(\Doctrine\ORM\EntityManager $entityManager, 
            \Tatami\Modules\ModuleManager $moduleManager,
            \Tatami\Events\EventManager $eventManager)
    {
        $resources = $entityManager->getRepository('Entity\Resource')->findAll();
        foreach($resources as $resource)
        {
            $this->addResource($resource->getName());
        }
        
        $roles = $entityManager->getRepository('Entity\UserRole')->findAll();
        foreach($roles as $role)
        {
            if(is_object($role->getParent()))
                $this->addRole($role->getName(), $role->getParent()->getName());
            else $this->addRole($role->getName());
            
            foreach($role->getPermissions() as $permission)
            {
                $this->allow($role->getName(), $permission->getResource()->getName(), $permission->getPrivilege());
            }
        }
        
        $eventManager->fireEvent(\Tatami\Events\Event::PERMISSIONS_LOAD, $this);
    }
}