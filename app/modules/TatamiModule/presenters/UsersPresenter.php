<?php
namespace TatamiModule;
/**
 * Description of UsersPresenter
 *
 * @author Martin
 */
class UsersPresenter extends \Tatami\Modules\ModulePresenter
{
    protected $toolbar = 'users';
    
    public function renderDefault()
    {
        $limit = 10;
        $offset = 0;
        $users = $this->em->getRepository('Entity\User');//->findBy(array(), null, $limit, $offset);
        
    }
    
    protected function createComponentUsersEditor($name)
    {
        $editor = new \Tatami\Components\UsersEditor($this, $name);
        $repository = $this->em->getRepository('Entity\User');
        $editor->setRepository($repository);
    }
}