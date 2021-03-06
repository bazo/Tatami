<?php
namespace Gridder\Columns;
use Nette\Application\UI\Control;
use Nette\Utils\Strings;
use Gridder\Actions;
/**
 * Description of ActionColumn
 *
 * @author Martin
 */
class ActionColumn extends Control
{
    private 
	$presenter
    ;

    public function setPresenter(&$presenter)
    {
	$this->presenter = $presenter;
    }
    
    /**
     * Adds normal action
     * @param string $title
     * @param string $destination
     * @param bool $ajax
     * @return Actions\Action
     */
    public function addAction($title, $destination, $ajax = false)
    {
        $title = Strings::lower($title);
        $action = new Actions\Action($this, $title);
	$action->setPresenter($this->presenter);
        $action->setTitle($title);
        $action->setDestination($destination);
        $action->setAjax($ajax);
        return $action;
    }

    /**
     * Adds dynamic action
     * @param string $title
     * @param bool $ajax
     * @return DynamicAction
     */
    public function addDynamicAction($title, $ajax = false)
    {
        $title = Strings::lower($title);
        $action = new \Gridder\Actions\DynamicAction($this, $title);
        $action->setTitle($title);
        $action->setAjax($ajax);
        return $action;
    }
    
    
    /**
     *
     * @param mixed $value
     * @param DibiRow $record
     * @return ActionColumn
     */
    public function setRecord($value, $record)
    {
        $this->value = $value;
        $this->record = $record;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function renderHeader()
    {
        return $this->name;
    }

    

    /**
     * gets action
     * @param string $action
     * @return DibiDatagrid_Action
     */
    public function getAction($action)
    {
        $action = String::lower($action);
        return $this->getComponent($action);
    }

    /**
     * hides action
     * @param string $action
     * @return DibiDatagrid_ActionColumn
     */
    public function hideAction($action)
    {
        $action = String::lower($action);
        $this->removeComponent($this->getComponent($action));
        return $this;
    }

    /**
     * Gets all actions
     * @return array
     */
    public function getActions()
    {
        return $this->getComponents(false, 'Gridder\Actions\IAction');
    }
    
    public function getActionsCount()
    {
	return count($this->getActions());
    }
}