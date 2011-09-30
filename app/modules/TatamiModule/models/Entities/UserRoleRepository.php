<?php
namespace Tatami\Models\Repositories;
class UserRoleRepository extends EntityRepository
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
            $children = $role->getChildren();
            if(!empty($children)) 
                $this->parseRoles ($children, $result[$roleId]['children']);
        }
        return $result;
    }
    
    public function getDropdownValues()
    {
	$roles = $this->createQueryBuilder('userRole')
		->select('userRole.id as id, userRole.name as name')
		//->where('userRole.parent IS NULL')
		->getQuery()
		->getArrayResult();
	foreach($roles as $role)
	{
	    $values[(int)$role['id']] = $role['name'];
	}
	return $values;
    }
}