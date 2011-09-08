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
	$zip = \Tatami\Tools\Zip::open('test.zip');
        foreach($roles as $role)
        {
            $roleId = $role->getId();
            $result[$roleId]['parent'] = $role->getName();
            $children = $role->getChildren();
            if(!empty($children)) 
                $this->parseRoles ($children, $result[$roleId]['children']);
        }
        return $result;
    }
    
    public function getDropdownValues()
    {
	
    }
}