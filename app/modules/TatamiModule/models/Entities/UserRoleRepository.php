<?php
namespace Repositories;
class UserRoleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getRolesTree()
    {
        $roles = $this->createQueryBuilder('userRole')->where('userRole.parent IS NULL')->getQuery()->execute();
        return $this->parseRoles($roles);
    }
    
    private function parseRoles($roles, &$result = array())
    {
        foreach($roles as $role)
        {
            $roleId = $role->getId();
            $result[$roleId]['parent'] = $role->getName();
            if($role->getParent() == null)
            {
                //$result[$roleId]['parent'] = $role->getName();
            }
            else
            {
                //$result[$role->getParent()->getId()][$role->getId()] = $role->getName();
            }
            $children = $role->getChildren();
            if(!empty($children)) 
                $this->parseRoles ($children, $result[$roleId]['children']);
        }
        return $result;
    }
}