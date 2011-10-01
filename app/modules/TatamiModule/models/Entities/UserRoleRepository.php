<?php
namespace Tatami\Models\Repositories;
class UserRoleRepository extends EntityRepository
{
    public function getPermissionsForRole($id)
    {
	$permissions = array();
	$permissionsCollection = $this->find($id)->permissions;
	foreach($permissionsCollection as $permission)
	{
	    $permissions[$permission->resource->name][$permission->privilege] = true;
	}
	return $permissions;
    }
}