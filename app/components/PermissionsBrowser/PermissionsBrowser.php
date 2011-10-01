<?php
namespace Tatami\Components;
use Tatami\Models\Repositories\UserRoleRepository;

class PermissionsBrowser extends BaseControl
{
    private 
        /** @var \Repositories\UserRoleRepository */
        $repository
    ;
    
    public function setRepository(UserRoleRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function renderGrid()
    {
	$this->template->setFile(__DIR__.'/grid.latte');
	$this->template->render();
    }
}