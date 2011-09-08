<?php
namespace Tatami\Components;

class UsersEditor extends BaseControl
{
    private 
        /** @var \Repositories\UserRoleRepository */
        $repository,
	    
	$userId
    ;
    
    public function setRepository(\Doctrine\Common\Persistence\ObjectRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function render()
    {
        $this->template->setFile(__DIR__.'/tree.latte');
        $limit = 10;
        $offset = 0;
        $this->template->users = $this->repository->findBy(array(), null, $limit, $offset);;
	$this->template->render();
    }
    
    public function renderTree()
    {
	$this->template->setFile(__DIR__.'/tree.latte');
        $limit = 10;
        $offset = 0;
        $this->template->users = $this->repository->findBy(array(), null, $limit, $offset);;
	$this->template->render();
    }
    
    public function renderGrid()
    {
	$this->template->setFile(__DIR__.'/grid.latte');
	$this->template->render();
    }
    
    public function handleeditUser($id)
    {
        $this->template->id = $id;
        $this->template->setFile(__DIR__.'/userEdit.latte');
	$this->userId = $id;
        $this->refreshPopup();
        $this->template->render();
    }
    
    protected function createComponentFormTest($name)
    {
        $form = new \Tatami\Forms\AjaxForm($this, $name);
	$form->addHidden('id', $this->userId);
	$roles = $this->repository->getRolesTree();
	var_dump($roles);exit;
        $form->addText('test', 'Test');
        $form->addSubmit('btnSubmit', 'Submit')->onClick[] = callback($this, 'testFormSubmitted');
                
    }
    
    public function testFormSubmitted($button)
    {
        var_dump($button->form->values);
    }
}
