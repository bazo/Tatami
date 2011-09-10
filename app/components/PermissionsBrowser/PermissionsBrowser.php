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
    
    public function renderTree()
    {
	$this->template->setFile(__DIR__.'/tree.latte');
        $this->template->items = $this->repository->getRolesTree();
	$this->template->render();
    }
    
    public function renderGrid()
    {
	$this->template->setFile(__DIR__.'/grid.latte');
	$this->template->render();
    }
}